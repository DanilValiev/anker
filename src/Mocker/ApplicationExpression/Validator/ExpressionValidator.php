<?php

namespace App\Mocker\ApplicationExpression\Validator;

use App\Mocker\Exceptions\Expressions\InvalidExpressionStructureException;

class ExpressionValidator
{
    /**
     * @throws InvalidExpressionStructureException
     */
    public static function validate($tree, $data): bool
    {
        if (isset($tree['operator'])) {
            if ($tree['operator'] == '&&') {
                return self::validate($tree['left'], $data) && self::validate($tree['right'], $data);
            } elseif ($tree['operator'] == '||') {
                return self::validate($tree['left'], $data) || self::validate($tree['right'], $data);
            }
        } else {
            if (!is_array($tree)) {
                throw new InvalidExpressionStructureException('Invalid tree structure: ' . json_encode($tree));
            }

            if (count($tree) == 1 && isset($tree[0]['operator'])) {
                return self::validate($tree[0], $data);
            }

            $left = $tree[1] ?? null;
            $operator = $tree[0] ?? null;
            $right = $tree[2] ?? null;

            if ($left === null || $operator === null || $right === null) {
                throw new InvalidExpressionStructureException('Invalid condition structure: ' . json_encode($tree));
            }

            if (!isset($data[$left])) {
                return false;
            }

            $value = $data[$left];
            if ($operator == '=') {
                return $value == $right;
            } elseif ($operator == '!=') {
                return $value != $right;
            }
        }

        return false;
    }
}