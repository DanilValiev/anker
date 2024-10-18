<?php

namespace App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint;

use App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Data\DataCrudController;
use App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\Parameters\ParametersCrudController;
use App\Modules\Admin\Application\Api\Crud\Mocker\ScopesCrudController;
use App\Modules\Admin\Application\Api\Crud\Proxy\ProxyCrudController;
use App\Modules\Admin\Application\Api\Crud\Proxy\ProxyLogCrudController;
use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Enum\Roles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
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
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактирование эндпойнта');
    }

    public function configureActions(Actions $actions): Actions
    {
        $userRoles = $this->getUser()->getRoles();

        if (!in_array(Roles::ROLE_SUPER_ADMIN->value, $userRoles) && $userRoles > 1) {
            $actions
                ->setPermission(Action::NEW, Roles::ROLE_USER->value)
                ->setPermission(Action::EDIT, Roles::ROLE_USER->value)
                ->setPermission(Action::DELETE, Roles::ROLE_USER->value)
                ->setPermission(Action::SAVE_AND_CONTINUE, Roles::ROLE_USER->value)
                ->setPermission(Action::SAVE_AND_RETURN, Roles::ROLE_USER->value);
        }

        return
            $actions
                ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
                ->remove(Crud::PAGE_INDEX, Action::DETAIL)
                ->add(Crud::PAGE_INDEX, Action::DELETE);
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
        $fields = [
            FormField::addTab('Главное'),
            IdField::new('id')->hideOnForm(),
            TextField::new('slug')->setLabel('Путь')->setHelp('Префикс пути, Например test'),
            AssociationField::new('apiScopes')->setLabel('Неймспейс')->setHelp('Неймспейс этого эндпойнта')->setCrudController(ScopesCrudController::class),

            ChoiceField::new('methods')->setChoices([
                'Любой' => 'ANY', 'POST' => 'POST', 'GET' => 'GET',
                'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE'
            ])->setEmptyData('ANY')->setHelp('Разрешенный http метод')->setLabel('HTTP Метод')->setEmptyData('ANY'),

            BooleanField::new('active')->setHelp('Доступен ли эндпойнт')->setLabel('Активен')->setEmptyData(true),
            IntegerField::new('sleepTime')->setHelp('Задержка в ответе, указывать в секундах (допустим значение 1 даст время ответа 1+ сек)')->setLabel('Задержка (мс)')->setColumns(6)->hideOnIndex(),

            FormField::addTab('Проксирование'),
            CollectionField::new('proxy')->useEntryCrudForm(ProxyCrudController::class)->setLabel('Шаблоны проксирования')->setHelp('Шаблоны для проксирования запроса')->hideOnIndex()->setColumns(10),
            CollectionField::new('proxyLogs')->useEntryCrudForm(ProxyLogCrudController::class)->setLabel('Логи прокси запросов')->setHelp('Логи совершенных прокси запросов')->hideOnIndex()->allowAdd(false)->allowDelete(false),
        ];

        if ($pageName !== Crud::PAGE_NEW) {
            $fields[] = FormField::addTab('Параметры');
            $fields[] = CollectionField::new('parameters'
            )->useEntryCrudForm(ParametersCrudController::class)
                ->setColumns(13)->setHelp('Перечень параметров, ожидаемых в данном запросе')
                ->setLabel('Параметры запроса')
                ->hideOnIndex()
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]);

            $fields[] = FormField::addTab('Возвращаемые значения');
            $fields[] = CollectionField::new('data')
                ->useEntryCrudForm(DataCrudController::class)
                ->setColumns(13)
                ->setHelp('Варианты ответов')
                ->setLabel('Ответы')
                ->hideOnIndex()
                ->setFormTypeOptions([
                     'by_reference' => false,
                ]);
        } else {
            $fields[] = FormField::addPanel('Настройте параметры и возвращаемые значения после');
        }

        return $fields;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $userRoles = $user->getRoles();
            $qb->join('entity.apiScopes', 'apiScopes')
                ->andWhere('apiScopes.slug IN (:roles)')
                ->setParameter('roles', $userRoles);
        }

        return $qb;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Endpoint $entityInstance
     * @return void
     * @throws \Exception
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getApiScopes()->getSlug()
            || in_array($entityInstance->getApiScopes()->getSlug(), $this->getUser()->getRoles())
            || in_array(Roles::ROLE_SUPER_ADMIN->value, $this->getUser()->getRoles())) {
                parent::persistEntity($entityManager, $entityInstance);
        } else {
            throw new \Exception('Access denied');
        }
    }
}
