<?php

namespace App\Modules\Admin\Application\Api\System\Security;

use App\Modules\Admin\Application\Api\Crud\Mocker\Endpoint\EndpointCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly AdminUrlGenerator $urlGenerator
    ) {}
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirect($this->urlGenerator->setController(EndpointCrudController::class)->generateUrl());
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'page_title' => 'Анкер Admin',
            'csrf_token_intention' => 'authenticate',
            'target_path' => getallheaders()['Referer'] ?? $this->generateUrl('admin'),
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'username_label' => 'Ваш логин',
            'password_label' => 'Ваш пароль',
            'sign_in_label' => 'Войти',
        ]);
    }
}