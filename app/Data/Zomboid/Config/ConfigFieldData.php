<?php

namespace App\Data\Zomboid\Config;

use Spatie\LaravelData\Data;

class ConfigFieldData extends Data
{
    public function __construct(
        public string $fieldName,
        public string $defaultValue,
        public string $fieldType,
        public string $description = '',
    ) {}
}
