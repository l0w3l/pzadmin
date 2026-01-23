<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Config;

use App\Data\Zomboid\Config\ConfigFieldData;
use App\Data\Zomboid\Config\ConfigFieldTypeEnum;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class ConfigService extends AbstractService implements ConfigServiceInterface
{
    public function __construct(
        public string $zomboidPath,
        public string $serverName,
    ) {}

    public function parseMainIniFile(): array
    {
        $iniFilePath = $this->zomboidPath.$this->serverName.'.ini';
        dump($iniFilePath);

        if (! file_exists($iniFilePath)) {
            return [];
        }

        $fields = [];
        $file = file_get_contents($iniFilePath);
        $fileLines = explode("\n", $file);

        $description = '';
        foreach ($fileLines as $line) {
            if (str_starts_with($line, '#')) {
                $description .= trim(substr($line, 1)."\n");
            } elseif (str_contains($line, '=')) {
                [$fieldName, $fieldValue] = explode('=', $line, 2);

                $type = ConfigFieldTypeEnum::resolve($fieldName, $fieldValue);

                $fields[] = new ConfigFieldData($fieldName, $fieldValue, $type->value, $description);

                $description = '';
            }
        }

        return $fields;
    }
}
