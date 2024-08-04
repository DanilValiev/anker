<?php

namespace App\Modules\Mocker\Domain\ApplicationExpression;

interface ApplicationExpressionInterface
{
    public function process(string $expressionString, array $params): bool;
}