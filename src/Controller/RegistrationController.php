<?php

namespace Cooolinho\Bundle\SecurityBundle\Controller;

use Cooolinho\Bundle\SecurityBundle\DependencyInjection\Configuration;
use Cooolinho\Bundle\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;
use Cooolinho\Bundle\SecurityBundle\Exception\UserClassNotFoundException;
use Cooolinho\Bundle\SecurityBundle\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @throws UserClassNotFoundException
     */
    public function register(
        Request               $request,
        ParameterBagInterface $parameterBag
    ): Response
    {
        if (!class_exists($parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::USER_CLASS))) {
            throw new UserClassNotFoundException();
        }

        $userClass = $parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::USER_CLASS);

        $user = new $userClass();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPlainPassword($form->get('plainPassword')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute($parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . Configuration::ROUTE_LOGIN));
        }

        return $this->render('@CooolinhoSecurity/register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
