<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint;

use App\Modules\Admin\Application\Api\Crud\Proxy\ProxyCrudController;
use App\Modules\Admin\Application\Api\Crud\Proxy\ProxyLogCrudController;
use App\Shared\Domain\Entity\Mocker\Endpoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('apiScopes')
            ->add('methods')
            ->add('active');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Главное'),
            IdField::new('id')->hideOnForm(),
            TextField::new('slug')->setLabel('Путь')->setHelp('Префикс пути, Например test'),
            AssociationField::new('apiScopes')->setLabel('Неймспейс')->setHelp('Неймспейс этого эндпойнта'),

            ChoiceField::new('methods')->setChoices([
                'Любой' => 'ANY', 'POST' => 'POST', 'GET' => 'GET',
                'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE'
            ])->setEmptyData('ANY')->setHelp('Разрешенный http метод')->setLabel('HTTP Метод'),

            BooleanField::new('active')->setHelp('Доступен ли эндпойнт')->setLabel('Активен'),
            IntegerField::new('sleepTime')->setHelp('Задержка в ответе, указывать в секундах (допустим значение 1 даст время ответа 1+ сек)')->setLabel('Задержка (мс)')->setColumns(6)->hideOnIndex(),

            FormField::addTab('Параметры'),
            CollectionField::new('parameters')->useEntryCrudForm(EndpointParametersCrudController::class)->setColumns(13)->setHelp('Параметры запроса')->setLabel('Параметры')->hideOnIndex(),

            FormField::addTab('Возвращаемые значения'),
            CollectionField::new('data')->useEntryCrudForm(EndpointDataCrudController::class)->setColumns(13)->setHelp('Варианты ответов')->setLabel('Ответы')->hideOnIndex(),

            FormField::addTab('Проксирование'),
            CollectionField::new('proxy')->useEntryCrudForm(ProxyCrudController::class)->setLabel('Шаблоны проксирования')->setHelp('Шаблоны для проксирования запроса')->hideOnIndex()->setColumns(10),
            CollectionField::new('proxyLogs')->useEntryCrudForm(ProxyLogCrudController::class)->setLabel('Логи прокси запросов')->setHelp('Логи совершенных прокси запросов')->hideOnIndex()->allowAdd(false)->allowDelete(false),
        ];
    }
}
