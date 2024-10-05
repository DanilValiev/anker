<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Data;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointDataResponseVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;

class EndpointDataResponseVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EndpointDataResponseVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            CodeEditorField::new('data')
                ->setLabel('Ответ')
                ->setCustomOptions(['lineNumbers' => true])
                ->setHtmlAttribute('style', 'height: auto; min-height: 200px')
                ->setHelp('Ответ в формате json'),
            BooleanField::new('active')->setLabel('Активен'),
        ];
    }
}
