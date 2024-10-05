<?php

namespace App\Modules\Admin\Application\Api\Crud\System;

use App\Shared\Domain\Entity\System\User;
use App\Shared\Domain\Enum\Roles;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Список пользователей')
            ->setPageTitle(Crud::PAGE_NEW, 'Создание пользователя')
            ->setPageTitle(Crud::PAGE_EDIT, 'Изменение пользователя');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        if (in_array(Roles::ROLE_SUPER_ADMIN->value, $this->getUser()->getRoles())) {
            return [
                TextField::new('name')->setLabel('Имя пользователя')->setHelp('Логин с которого человек будет заходить'),
                TextField::new('plainPassword', 'Password')
                    ->setLabel('Пароль')
                    ->setHelp('Пароль пользователя в открытом виде')
                    ->onlyOnForms()
                    ->setFormTypeOptions(['required' => $pageName === Crud::PAGE_NEW]),
                ArrayField::new('roles')
                    ->setLabel('Роли пользователя')
                    ->setHelp('Для доступа к скопу необходимо создать роль дублирующий slug скоупа'),
            ];
        }

        return [];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function encodePassword(User $user): void
    {
        if ($user->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
    }
}
