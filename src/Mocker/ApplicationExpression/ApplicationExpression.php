<?php

namespace App\Mocker\ApplicationExpression;

use App\Mocker\ApplicationExpression\Parser\ExpressionParser;
use App\Mocker\ApplicationExpression\Validator\ExpressionValidator;
use App\Mocker\Exceptions\Expressions\InvalidExpressionStructureException;

class ApplicationExpression implements ApplicationExpressionInterface
{
    public function __construct(
        private readonly ExpressionParser $expressionParser
    )
    {
    }

    /**
     * @param string $expressionString "(param1 = text1 && param2 != text2) || param3 = test"
     *
     * @throws InvalidExpressionStructureException
     */
    public function process(string $expressionString, array $params): bool
    {
        $parsedTree = $this->expressionParser->parse($expressionString);

        return ExpressionValidator::validate($parsedTree, $params);
    }
}