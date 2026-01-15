<?php

declare(strict_types=1);

namespace App\Data\Zomboid\Config;

enum ConfigFieldTypeEnum: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case BOOL = 'bool';
    case LIST = 'list';

    public static function resolve(string $fieldName, string $fieldValue): self
    {
        if ($fieldValue === 'false' || $fieldValue === 'true') {
            return self::BOOL;
        } elseif (str_contains($fieldValue, ';')) {
            return self::LIST;
        } elseif (is_numeric($fieldValue)) {
            return self::NUMBER;
        } else {
            return self::customField($fieldName, $fieldValue);
        }
    }

    private static function customField(string $fieldName, string $fieldValue): self
    {
        return match ($fieldName) {
            'WorkshopItems' => self::LIST,
            'Mods' => self::LIST,
            default => self::STRING,
        };
    }
}
