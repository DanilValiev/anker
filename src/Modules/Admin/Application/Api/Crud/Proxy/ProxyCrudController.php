<?php

namespace App\Modules\Admin\Application\Api\Crud\Proxy;

use App\Shared\Domain\Entity\Proxy\Proxy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProxyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Proxy::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Проксирование запросов')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактирование шаблона проксирования')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Просмотр шаблона проксирования');
    }

    public function configureFields(string $pageName): iterable
    {
        $model = [
            FormField::addTab('Main'),
            IdField::new('id')->setColumns(8),
            TextField::new('name')->setLabel('Название')->setHelp('Название шаблона проксирования'),
            TextField::new('swappedUrl')->setLabel('Путь до прокси')->setHelp('Путь по которому будет доступен прокси'),
            BooleanField::new('active')->setLabel('Активно')->setHelp('Активно ли прокси'),
            IntegerField::new('weight')->setLabel('Вес прокси')->setHelp('Вес прокси при множественных их вариациях. Сначала вызывается прокси с большим весом')->hideOnIndex(),
            AssociationField::new('nextProxy')->setLabel('Следующий прокси')->setHelp('Прокси который будет выполнен после этого')->hideOnIndex(),

            FormField::addTab('Данные запроса'),
            UrlField::new('url')->setLabel('Ссылка ресурса')->setHelp('Ссылка по которой необходимо выполнить запрос')->hideOnIndex(),
            ChoiceField::new('method')->setChoices([
                'POST' => 'POST', 'GET' => 'GET', 'PUT' => 'PUT',
                'PATCH' => 'PATCH', 'DELETE' => 'DELETE'
            ])->setLabel('HTTP метод')->setHelp('Метод по которому надо совершить запрос'),
            ChoiceField::new('parametersBagType')->setChoices([
                'Не отправлять' => 'none', 'В json' => 'body',
                'В форме' => 'form', 'В ссылке' => 'query'
            ])->setLabel('Куда вставлять параметры')->setHelp('Куда отправить проксированные параметры')->hideOnIndex(),
            CodeEditorField::new('additionalHeaders')->setLabel('Добавочные хедеры (json)')->setHelp('Хедеры в формате json, которые необходимо дополнительно встроить в запрос')->hideOnIndex(),
        ];

        foreach ($model as $item) {
            $item->setColumns(8);
        }

        return $model;
    }
}
