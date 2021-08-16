<?php

namespace Cooolinho\Bundle\SecurityBundle\Controller;

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
        Request $request,
        ParameterBagInterface $parameterBag
    ): Response
    {
        if (!class_exists($parameterBag->get('cooolinho_security.user_class'))) {
            throw new UserClassNotFoundException();
        }

        $userClass = $parameterBag->get('cooolinho_security.user_class');

        $user = new $userClass();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPlainPassword($form->get('plainPassword')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute($parameterBag->get('cooolinho_security.route_login'));
        }

        return $this->render('@CooolinhoSecurity/register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
