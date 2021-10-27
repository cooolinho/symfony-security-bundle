<?php

namespace Cooolinho\Bundle\SecurityBundle\Event;

use Cooolinho\Bundle\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationEvent
{
    public const PRE_PERSIST = 'cooolinho.security.registration.pre_persist';
    public const POST_PERSIST = 'cooolinho.security.registration.post_persist';
    public const REGISTRATION_SUCCESS = 'cooolinho.security.registration.registration_success';

    protected Request $request;
    protected UserInterface $user;
    protected FormInterface $form;
    protected Response $response;

    public function __construct(
        Request       $request,
        UserInterface $user,
        FormInterface $form,
        Response      $response
    )
    {
        $this->request = $request;
        $this->user = $user;
        $this->form = $form;
        $this->response = $response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return RegistrationEvent
     */
    public function setResponse(Response $response): RegistrationEvent
    {
        $this->response = $response;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->form->get('token')->getData();
    }
}