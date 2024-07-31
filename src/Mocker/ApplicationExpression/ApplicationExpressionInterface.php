<?php

namespace App\Mocker\ApplicationExpression;

use App\Mocker\Exceptions\Expressions\InvalidExpressionStructureException;

interface ApplicationExpressionInterface
{
    /**
     * @param string $expressionString "(param1 = text1 && param2 != text2) || param3 = test"
     * @param array $params
     *
     * @throws InvalidExpressionStructureException
     */
    public function process(string $expressionString, array $params): bool;
}