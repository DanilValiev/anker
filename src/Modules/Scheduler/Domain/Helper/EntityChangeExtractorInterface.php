<?php

namespace App\Modules\Scheduler\Domain\Helper;

interface EntityChangeExtractorInterface
{
    public function extract(object $entity, string $entityFqcn): array;
}