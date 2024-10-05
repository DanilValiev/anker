<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker;

use App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\EndpointCrudController;
use App\Shared\Domain\Entity\Mocker\ApiScope;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScopesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApiScope::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DELETE);
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
            TextareaField::new('description')->setLabel('Описание')->setHelp('Описание окружения'),
            BooleanField::new('active')->setLabel('Активен')->setHelp('Активен ли скоуп и все его эндпойнты'),

            FormField::addTab('Эндпойнты'),
            CollectionField::new('endpoints')->useEntryCrudForm(EndpointCrudController::class)->setColumns(10)->setLabel('Эндпойнты')->setHelp('Перечень созданных эндпоинтов')->hideOnIndex()
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $userRoles = $user->getRoles();
            $qb->andWhere('entity.slug IN (:roles)')
                ->setParameter('roles', $userRoles);
        }

        return $qb;
    }
}
