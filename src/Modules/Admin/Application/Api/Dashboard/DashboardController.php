<?php

namespace App\Modules\Admin\Application\Api\Dashboard;


use App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\EndpointCrudController;
use App\Modules\Admin\Application\Api\Crud\Mocker\ScopesCrudController;
use App\Shared\Domain\Entity\Mocker\ApiScope;
use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Entity\Mocker\ProcessLog;
use App\Shared\Domain\Entity\Proxy\Proxy;
use App\Shared\Domain\Entity\Proxy\ProxyLog;
use App\Shared\Domain\Entity\System\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use MarcinJozwikowski\EasyAdminPrettyUrls\Controller\PrettyDashboardController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends PrettyDashboardController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(EndpointCrudController::class)->generateUrl());
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserAvatar(true)
            ->displayUserName(true)
            ->setAvatarUrl('https://c8.alamy.com/comp/T43WG1/modern-abstract-art-portrait-of-four-people-vector-illustration-T43WG1.jpg')
        ;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Анкер - управляй мечтой!')
        ;
    }

    public function configureCrud(): Crud
    {
        return (parent::configureCrud())->addFormTheme('@EasyEditor/form/editor_widget.html.twig');
    }

    public function configureActions(): Actions
    {
        $actions = Actions::new();

        return $actions
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->setPermission(Action::NEW, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::SAVE_AND_CONTINUE, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::SAVE_AND_ADD_ANOTHER, 'ROLE_SUPER_ADMIN')
        ;
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()->addCssFile('css/admin/easyadmin_serenity_theme.css');
    }

//    public function configureAssets(): Assets
//    {
//        return (parent::configureAssets())
//            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css')
//            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/monokai.min.css')
//            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js')
//            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/edit/closebrackets.min.js')
//            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/lint/lint.min.js')
//            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/jsonlint/1.6.0/jsonlint.min.js')
//            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/lint/json-lint.min.js')
//            ->addJsFile('js/vendor/autosize.min.js')
//            ->addJsFile('js/admin/json-field.js')
//            ;
//    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Мокер');
        yield MenuItem::linkToCrud('▪ Неймспейсы', '', ApiScope::class);
        yield MenuItem::linkToCrud('▪ Эндпойнты', '', Endpoint::class);
        yield MenuItem::linkToCrud('▪ Логи запросов', '', ProcessLog::class);

        yield MenuItem::section('Прокси');
        yield MenuItem::linkToCrud('▪ Шаблоны проксирования', '', Proxy::class);
        yield MenuItem::linkToCrud('▪ Логи запросов', '', ProxyLog::class);

        yield MenuItem::section('Системное');
        yield MenuItem::linkToCrud('▪ Пользователи', '', User::class);
    }
}
