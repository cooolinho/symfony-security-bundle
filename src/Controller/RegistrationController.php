<?php

namespace Cooolinho\Bundle\SecurityBundle\Controller;

use Cooolinho\Bundle\SecurityBundle\Event\RegistrationEvent;
use Cooolinho\Bundle\SecurityBundle\Exception\RegistrationFormNotFoundException;
use Cooolinho\Bundle\SecurityBundle\Exception\UserClassNotFoundException;
use Cooolinho\Bundle\SecurityBundle\Service\ConfigurationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/register", name="app_register")
     * @throws UserClassNotFoundException
     * @throws RegistrationFormNotFoundException
     */
    public function register(Request $request, ConfigurationService $configuration): Response
    {
        if (!class_exists($configuration->getUserClass())) {
            throw new UserClassNotFoundException();
        }

        if (!class_exists($configuration->getRegistrationForm())) {
            throw new RegistrationFormNotFoundException();
        }

        $userClass = $configuration->getUserClass();
        $registrationForm = $configuration->getRegistrationForm();

        $user = new $userClass();
        $form = $this->createForm($registrationForm, $user);
        $form->handleRequest($request);

        $event = new RegistrationEvent($request, $user, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPlainPassword($form->get('plainPassword')->getData());

            $entityManager = $this->getDoctrine()->getManager();

            $this->eventDispatcher->dispatch($event, RegistrationEvent::PRE_PERSIST);
            $entityManager->persist($user);
            $this->eventDispatcher->dispatch($event, RegistrationEvent::POST_PERSIST);
            $entityManager->flush();

            return $this->redirectToRoute($configuration->getRouteLogin());
        }

        return $this->render('@CooolinhoSecurity/register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
