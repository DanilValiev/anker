<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Crud\Endpoint\EndpointCrudController;
use App\Shared\Doctrine\Entity\Mocker\ApiScope;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScopesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApiScope::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Главная'),
            IdField::new('id')->hideOnForm(),
            TextField::new('slug')->setLabel('Путь')->setHelp('Путь до окружения'),
            TextEditorField::new('description')->setLabel('Описание')->setHelp('Описание окружения'),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли скоуп и все его эндпойнты'),
            FormField::addTab('Эндпойнты'),
            CollectionField::new('endpoints')->useEntryCrudForm(EndpointCrudController::class)->setColumns(10)->setLabel('Эндпойнты')->setHelp('Перечень созданных эндпоинтов')
        ];
    }
}
