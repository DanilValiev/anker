<?php

namespace App\Controller\Admin\Crud\System;

use App\Controller\Admin\Crud\Endpoint\EndpointCrudController;
use App\Controller\Admin\Crud\ScopesCrudController;
use App\Shared\Doctrine\Entity\Mocker\ProcessLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProcessLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProcessLog::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Связи'),
            AssociationField::new('scope')->setCrudController(ScopesCrudController::class)->setLabel('Неймспейс')->setHelp('Глобальный неймспейс запроса'),
            AssociationField::new('endpoint')->setCrudController(EndpointCrudController::class)->setLabel('Эндпойнт')->setHelp('Эндпойнт запроса'),

            FormField::addTab('Основное'),
            TextField::new('method')->setLabel('HTTP Метод')->setHelp('HTTP метод по которому был совершен этот запрос'),
            DateTimeField::new('requestTime')->setLabel('Время запроса')->setHelp('Время когда был совершен запрос'),
            CodeEditorField::new('response')->setLabel('Отправленный ответ')->setHelp('Ответ отправившиеся пользователю'),
            IntegerField::new('responseCode')->setLabel('Отправленный код')->setHelp('Код ответа отправившиеся пользователю'),

            FormField::addTab('Пришедшие данные'),
            CodeEditorField::new('incomingHeaders')->setLabel('Пришедшие заголовки')->setHelp('Заголовки которые прислал юзер')->hideOnIndex(),
            CodeEditorField::new('incomingParams')->setLabel('Пришедшие параметры')->setHelp('Параметры которые прислал юзер')->hideOnIndex(),
            CodeEditorField::new('userIps')->setLabel('IP пользователя')->setHelp('Массив возможных ip адресов пользователя')->hideOnIndex(),
        ];
    }
}
