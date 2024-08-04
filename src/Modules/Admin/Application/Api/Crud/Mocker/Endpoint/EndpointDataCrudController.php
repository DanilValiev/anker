<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint;

use App\Shared\Domain\Entity\Mocker\EndpointData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EndpointData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('expression')->setLabel('Условие')->setHelp('Условие где && -> это и || -> это или =/!= -> соответственно. Например: (key1 = true || key2 = false) && key3 = true')->setColumns(7)->hideOnIndex(),
            IntegerField::new('statusCode')->setLabel('Статус код')->setHelp('HTTP код ответа')->setColumns(7),
            CodeEditorField::new('data')->setLabel('Ответ')->setHelp('Ответ в формате json')->setColumns(7),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли вариант ответа')->setColumns(7),
        ];
    }
}
