<?php

namespace App\Modules\Admin\Application\Api\Crud\Proxy;

use App\Shared\Domain\Entity\Proxy\ProxyLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProxyLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProxyLog::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Логи прокси запросов')
            ->setPageTitle(Crud::PAGE_EDIT, 'Глубокий просмотр лога прокси запроса')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Просмотр лога прокси запроса');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('proxy')->setCrudController(ProxyLog::class)->setLabel('Неймспейс')->setHelp('Глобальный неймспейс запроса'),
            DateTimeField::new('requestTime')->setLabel('Время запроса')->setHelp('Время когда был совершен запрос'),
            CodeEditorField::new('response')->setLabel('Отправленный ответ')->setHelp('Ответ отправившиеся пользователю'),
            IntegerField::new('responseCode')->setLabel('Отправленный код')->setHelp('Код ответа отправившиеся пользователю'),
        ];
    }
}
