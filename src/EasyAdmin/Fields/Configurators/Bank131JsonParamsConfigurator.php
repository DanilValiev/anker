<?php

namespace App\EasyAdmin\Fields\Configurators;

use App\EasyAdmin\Fields\Bank131JsonParamsField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class Bank131JsonParamsConfigurator implements FieldConfiguratorInterface
{
    public function __construct(private PropertyAccessorInterface $propertyAccessor) { }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return Bank131JsonParamsField::class === $field->getFieldFqcn();
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $propertyPath = $field->getProperty();
        $entityInstance = $entityDto->getInstance();
        $field->setFormTypeOption('mapped', false);

        $isPropertyReadable = (null !== $entityInstance) && $this->propertyAccessor->isReadable($entityInstance, $propertyPath);
        if (!$isPropertyReadable) {
            return ;
        }

        $value = $this->propertyAccessor->getValue($entityInstance, $propertyPath);
        $field->setFormTypeOption('json_string', $value);
        $field->setFormTypeOption('param_name', $propertyPath);
    }
}
