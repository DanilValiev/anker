<?php

namespace App\EasyAdmin\Fields;

use App\Form\JsonParamsType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class Bank131JsonParamsField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(JsonParamsType::class)
            ->addCssClass('card px-4 m-2')
        ;
    }
}