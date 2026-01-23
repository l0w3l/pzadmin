<?php

declare(strict_types=1);

namespace App\Services\Zomboid;

use App\Data\Zomboid\ServerData;
use App\Models\ZomboidBackup;
use App\Services\Docker\DockerServiceInterface;
use App\Services\Docker\Enums\ContainerActionEnum;
use App\Services\Zomboid\Rcon\RconServiceFactory;
use Lowel\LaravelServiceMaker\Services\AbstractService;
use RuntimeException;

class ZomboidService extends AbstractService implements ZomboidServiceInterface
{
    public function __construct(
        public DockerServiceInterface $zomboidDockerContainer,
        public RconServiceFactory $rconServiceFactory,
    ) {}

    public function getServer(): ServerData
    {
        $serverInspection = $this->zomboidDockerContainer->status();

        return ServerData::fromInspectionResult($serverInspection);
    }

    public function doStart(): bool
    {
        $lastBackup = ZomboidBackup::latest()->first();

        if ($lastBackup === null || $lastBackup->hash !== $this->configHash()) {
            $this->backup();
        }

        return $this->zomboidDockerContainer->operate(ContainerActionEnum::UP);
    }

    public function doStop(): bool
    {
        $this->rconServiceFactory->zomboid()->save();

        return $this->zomboidDockerContainer->operate(ContainerActionEnum::DOWN);
    }

    public function doRestart(): bool
    {
        return $this->doStop() && $this->doStart();
    }

    public function backup(): string
    {
        $sourceFolder = base_path('/docker/zomboid/storage/data');
        $archiveFile = base_path('/docker/zomboid/backups/').date('Y-m-d_H-i-s').'.tar.gz';

        $cmd = sprintf(
            'tar -czf %s -C %s .',
            escapeshellarg($archiveFile),
            escapeshellarg($sourceFolder)
        );

        exec($cmd, $output, $code);

        if ($code !== 0) {
            throw new RuntimeException('Backup failed');
        }

        ZomboidBackup::create([
            'file_path' => $archiveFile,
            'file_size' => filesize($archiveFile),
            'hash' => $this->configHash(),
        ]);

        return $archiveFile;
    }

    protected function configHash(): string
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
}
