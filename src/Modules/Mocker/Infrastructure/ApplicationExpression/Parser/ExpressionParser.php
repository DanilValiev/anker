<?php

namespace App\Modules\Mocker\Infrastructure\ApplicationExpression\Parser;

use App\Modules\Mocker\Domain\Process\Exceptions\Expressions\InvalidExpressionStructureException;

class ExpressionParser {
    private $tokens;
    private $pos;

    /**
     * @throws InvalidExpressionStructureException
     */
    public function parse($conditionString): ?array
    {
        $conditionString = preg_replace('/([()])/', ' $1 ', $conditionString);
        $this->tokens = preg_split('/\s+/', $conditionString);
        $this->pos = 0;

        return $this->parseExpression();
    }

    /**
     * @throws InvalidExpressionStructureException
     */
    private function parseExpression(): ?array
    {
        $expressions = [];
        $current = [];

        while ($this->pos < count($this->tokens)) {
            $token = $this->tokens[$this->pos++];

            if ($token == '(') {
                $current[] = $this->parseExpression();
            } elseif ($token == ')') {
                break;
            } elseif (in_array($token, ['&&', '||'])) {
                if (!empty($current)) {
                    $expressions[] = $current;
                }
                $expressions[] = $token;
                $current = [];
            } else {
                $current[] = $token;
            }
        }

        if (!empty($current)) {
            $expressions[] = $current;
        }

        return $this->buildTree($expressions);
    }

    /**
     * @throws InvalidExpressionStructureException
     */
    private function buildTree($expressions): ?array
    {
        if (count($expressions) == 1) {
            return [
                'operator' => '&&',
                'left' => $this->parseCondition($expressions[0]),
                'right' => $this->parseCondition($expressions[0])
            ];
        }

        $tree = null;
        $i = 0;

        while ($i < count($expressions)) {
            if ($expressions[$i] == '||') {
                $tree = [
                    'operator' => '||',
                    'left' => $tree ?? $this->parseCondition($expressions[$i - 1]),
                    'right' => $this->buildTree(array_slice($expressions, $i + 1))
                ];
                break;
            } elseif ($expressions[$i] == '&&') {
                $tree = [
                    'operator' => '&&',
                    'left' => $tree ?? $this->parseCondition($expressions[$i - 1]),
                    'right' => $this->parseCondition($expressions[++$i])
                ];
            } else {
                $tree = $this->parseCondition($expressions[$i]);
            }
            $i++;
        }

        return $tree;
    }

    /**
     * @throws InvalidExpressionStructureException
     */
    private function parseCondition($condition): array
    {
        if (is_array($condition)) {
            if (count($condition) == 1 && is_array($condition[0])) {
                return $condition[0];
            }

            return [
                'left' => $condition[0],
                1 => $condition[1],
                'right' => $condition[2]
            ];
        }

        preg_match('/(\w+)\s*(!=|=)\s*(\w+)/', implode(' ', $condition), $matches);

        if (count($matches) != 4) {
            throw new InvalidExpressionStructureException('Invalid condition: ' . implode(' ', $condition));
        }

        return [
            'left' => $matches[1],
            'operator' => $matches[2],
            'right' => $matches[3]
        ];
    }
}