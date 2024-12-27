<?php

namespace Domain\Files\Mappers;

class ModelToIdentifierMapper
{
    public static function map(string $model): string
    {
        $modelName = class_basename($model);
        $mapping = [
            'User' => 'user-avatar',
            'Trademark' => 'trademark-image',
            'Event' => 'event-banner',
        ];

        if ($model === 'Domain\Documents\Models\Document') {
            return $mapping[$modelName] ?? strtolower($modelName);
        }

        return $mapping[$modelName] ?? strtolower($modelName) . '-image';
    }
}
