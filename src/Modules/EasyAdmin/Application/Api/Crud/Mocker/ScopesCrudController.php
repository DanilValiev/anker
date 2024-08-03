<?php

namespace App\Modules\EasyAdmin\Application\Api\Crud\Mocker;

use App\Modules\EasyAdmin\Application\Api\Crud\Mocker\Endpoint\EndpointCrudController;
use App\Shared\Domain\Entity\Mocker\ApiScope;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Список неймспейсов')
            ->setPageTitle(Crud::PAGE_NEW, 'Создание неймспейса')
            ->setPageTitle(Crud::PAGE_EDIT, 'Изменение неймспейса')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Просмотр неймспейса');
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
            CollectionField::new('endpoints')->useEntryCrudForm(EndpointCrudController::class)->setColumns(10)->setLabel('Эндпойнты')->setHelp('Перечень созданных эндпоинтов')->hideOnIndex()
        ];
    }
}
