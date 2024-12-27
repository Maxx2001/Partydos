<?php

namespace App\Web\Files\Actions;

use Domain\Files\Mappers\ModelToIdentifierMapper;

class GenerateFileUrl
{
    public function execute(string $modelType, int $modelId, int $fileId, string $fileName): string
    {
        $identifier = ModelToIdentifierMapper::map($modelType);

        $relativeUrl = $identifier . "/" . $fileId ;
        return url($relativeUrl);
    }

}
