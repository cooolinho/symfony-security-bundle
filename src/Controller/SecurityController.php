<?php

namespace Cooolinho\Bundle\SecurityBundle\Controller;

use Cooolinho\Bundle\SecurityBundle\DependencyInjection\Configuration;
use Cooolinho\Bundle\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;
use Cooolinho\Bundle\SecurityBundle\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class, null, [
            'lastUsername' => $lastUsername,
            'login_property' => $this->parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::LOGIN_PROVIDER_PROPERTY),
        ]);

        return $this->render(
            '@CooolinhoSecurity/security/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error,
                'registration_enabled' => $this->parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::REGISTRATION_ENABLED),
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
