<?php

namespace Cooolinho\Bundle\SecurityBundle\Form\Traits;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

trait PlainPasswordTrait
{
    public function getPasswordConstraints(FormBuilderInterface $builder, TranslatorInterface $translator): array
    {
        return [
            new NotBlank([
                'message' => $translator->trans(
                    'security.change_password.message.not_blank',
                    [],
                    CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
            ]),
            new Length([
                'min' => 6,
                'minMessage' => $translator->trans(
                    'security.change_password.message.min_length',
                    [], CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
                'max' => 4096,
            ]),
        ];
    }
}
