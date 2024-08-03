<?php

namespace App\Modules\Mocker\Infrastructure\ApplicationExpression;

use App\Modules\Mocker\Domain\Process\Exceptions\Expressions\InvalidExpressionStructureException;
use App\Modules\Mocker\Infrastructure\ApplicationExpression\Parser\ExpressionParser;
use App\Modules\Mocker\Infrastructure\ApplicationExpression\Validator\ExpressionValidator;

class ApplicationExpression
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