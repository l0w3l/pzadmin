<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Backup;

use App\Models\ZomboidBackup;
use Cache;
use Lowel\LaravelServiceMaker\Services\AbstractService;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Paranormal;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

class BackupService extends AbstractService implements BackupServiceInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws UpdateNotFoundInCurrentContextException
     */
    public function backup(): false|string
    {
        $this->setProgress(true);

        $sourceFolder = base_path('/docker/zomboid/storage/data');
        $archiveFile = base_path('/docker/zomboid/backups/').date('Y-m-d_H-i-s').'.tar.gz';

        $compressor = trim(shell_exec('command -v pigz')) ? 'pigz -1' : 'gzip -1';

        $cmd = sprintf(
            'tar -I %s -cf %s -C %s .',
            escapeshellarg($compressor),
            escapeshellarg($archiveFile),
            escapeshellarg($sourceFolder)
        );

        exec($cmd, $output, $code);

        if ($code !== 0) {
            try {
                $update = Extrasense::update();

                Paranormal::catch($update, new RuntimeException('Backup failed'));
            } catch (UpdateNotFoundInCurrentContextException $e) {
                Cache::delete('zomboid:backup:in_progress');

                return false;
            } finally {
                $this->setProgress(false);
            }
        }

        ZomboidBackup::create([
            'file_path' => $archiveFile,
            'file_size' => filesize($archiveFile),
            'hash' => $this->configHash(),
        ]);

        $this->setProgress(false);

        return $archiveFile;
    }

    public function configHash(): string
    {
        $folder = base_path('docker/zomboid/storage/data/Server/');

        $files = collect(scandir($folder))
            ->reject(fn ($file) => in_array($file, ['.', '..']))
            ->sort()
            ->map(function ($file) use ($folder) {
                $path = $folder.'/'.$file;
                if (! is_file($path)) {
                    return '';
                }

                $content = file_get_contents($path);
                // cut off comments and empty lines
                $content = preg_replace('/^\s*(#|--).*/m', '', $content);
                $content = trim(preg_replace('/^\s*$/m', '', $content));

                return hash('sha256', $content);
            });

        return hash('sha256', $files->implode(''));
    }

    public function inProgress(): bool
    {
        return Cache::has(self::BACKUP_CACHE_KEY);
    }

    protected function setProgress(bool $inProgress): void
    {
        if ($inProgress) {
            Cache::forever(self::BACKUP_CACHE_KEY, true);
        } else {
            Cache::delete(self::BACKUP_CACHE_KEY);
        }
    }
}
