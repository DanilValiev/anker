<?php

namespace App\Controller\Admin\Crud\Endpoint;

use App\Entity\Festival;
use App\Shared\Doctrine\Entity\Mocker\Endpoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EndpointCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Endpoint::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $response = [
            IdField::new('id')->hideOnForm(),
            TextField::new('slug')->setHelp('Префикс пути, Например test'),
            ChoiceField::new('methods')->setChoices([
                'Любой' => 'ANY', 'POST' => 'POST', 'GET' => 'GET',
                'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE'
            ])->setEmptyData('ANY')->setHelp('Разрешенный http метод')->setLabel('HTTP Метод'),
            BooleanField::new('active')->setHelp('Доступен ли эндпойнт')->setLabel('Активен'),
            IntegerField::new('sleepTime')->setHelp('Задержка в ответе, указывать в мс (допустим значение 1000 даст время ответа 1+ сек)')->setLabel('Задержка (мс)'),
            CollectionField::new('params')->useEntryCrudForm(EndpointParametersCrudController::class)->setColumns(7)->setHelp('Параметры запроса')->setLabel('Параметры'),
            CollectionField::new('data')->useEntryCrudForm(EndpointDataCrudController::class)->setColumns(7)->setHelp('Варианты ответов')->setLabel('Ответы'),
        ];

        if ($pageName === Crud::PAGE_NEW) {
            $response[3]->setValue(3);
            $response[4]->setValue(0);
        }

        return $response;
    }

    public function createEntity(string $entityFqcn): Endpoint
    {
        $entity = new Endpoint();
        $entity->setActive(true);

        return $entity;
    }
}
