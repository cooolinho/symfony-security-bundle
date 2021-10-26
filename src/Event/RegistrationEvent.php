<?php

namespace Cooolinho\Bundle\SecurityBundle\Event;

use Cooolinho\Bundle\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationEvent
{
    public const PRE_PERSIST = 'cooolinho.security.registration.pre_persist';
    public const POST_PERSIST = 'cooolinho.security.registration.post_persist';

    protected Request $request;
    protected UserInterface $user;
    protected FormInterface $form;

    public function __construct(Request $request, UserInterface $user, FormInterface $form)
    {
        $this->request = $request;
        $this->user = $user;
        $this->form = $form;
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
}