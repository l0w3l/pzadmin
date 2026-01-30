<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Backup;

use App\Exceptions\Services\Zomboid\Backup\FailedToCreateBackupException;
use App\Models\ZomboidBackup;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class BackupService extends AbstractService implements BackupServiceInterface
{
    /**
     * @throws FailedToCreateBackupException
     */
    public function backup(): false|string
    {
        $sourceFolder = base_path('/docker/zomboid/storage/data');
        $archiveFile = base_path('/docker/zomboid/backups/').date('Y-m-d_H-i-s').'.tar.gz';

        $compressor = trim(shell_exec('command -v pigz') ? 'pigz -1' : 'gzip -1');

        $cmd = sprintf(
            'tar -I %s -cf %s -C %s .',
            escapeshellarg($compressor),
            escapeshellarg($archiveFile),
            escapeshellarg($sourceFolder)
        );

        exec($cmd, $output, $code);

        if ($code !== 0) {
            throw new FailedToCreateBackupException("Cannot create backup archive with \"{$cmd}\". Command exited with code {$code}. Output: ".implode("\n", $output));
        }

        ZomboidBackup::create([
            'file_path' => $archiveFile,
            'file_size' => filesize($archiveFile),
            'hash' => $this->configHash(),
        ]);

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

    public function backupRequired(): bool
    {
        $lastBackup = ZomboidBackup::latest()->first();

        return $lastBackup === null || $lastBackup->hash !== $this->configHash();
    }
}
