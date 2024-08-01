<?php

namespace App\Controller\Admin\Crud\Endpoint;

use App\Entity\Festival;
use App\Shared\Doctrine\Entity\Mocker\Endpoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Endpoint::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_NEW, 'Создание эндпойнта')
            ->setPageTitle(Crud::PAGE_INDEX, 'Список эндпойнтов')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактирование эндпойнта')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Главное'),
            IdField::new('id')->hideOnForm(),
            TextField::new('slug')->setHelp('Префикс пути, Например test'),

            ChoiceField::new('methods')->setChoices([
                'Любой' => 'ANY', 'POST' => 'POST', 'GET' => 'GET',
                'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE'
            ])->setEmptyData('ANY')->setHelp('Разрешенный http метод')->setLabel('HTTP Метод'),

            BooleanField::new('active')->setHelp('Доступен ли эндпойнт')->setLabel('Активен'),
            IntegerField::new('sleepTime')->setHelp('Задержка в ответе, указывать в секундах (допустим значение 1 даст время ответа 1+ сек)')->setLabel('Задержка (мс)')->setColumns(6)->hideOnIndex(),

            FormField::addTab('Параметры'),
            CollectionField::new('params')->useEntryCrudForm(EndpointParametersCrudController::class)->setColumns(13)->setHelp('Параметры запроса')->setLabel('Параметры')->hideOnIndex(),

            FormField::addTab('Возвращаемые значения'),
            CollectionField::new('data')->useEntryCrudForm(EndpointDataCrudController::class)->setColumns(13)->setHelp('Варианты ответов')->setLabel('Ответы')->hideOnIndex(),
        ];
    }
}
