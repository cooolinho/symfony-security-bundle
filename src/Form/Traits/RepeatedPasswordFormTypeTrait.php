<?php

namespace Cooolinho\Bundle\SecurityBundle\Form\Traits;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

trait RepeatedPasswordFormTypeTrait
{
    public function addRepeatedPasswordField(
        FormBuilderInterface $builder,
        TranslatorInterface $translator,
        string $child = 'plainPassword',
        bool $mapped = false
    ): void
    {
        $builder->add(
            $child,
            RepeatedType::class,
            $this->getRepeatedPasswordTypeOptions($translator, $mapped)
        );
    }

    public function getRepeatedPasswordTypeOptions(TranslatorInterface $translator, bool $isMapped = false): array
    {
        return [
            'type' => PasswordType::class,
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options' => [
                'label' => $this->translator->trans(
                    'security.change_password.password.new',
                    [],
                    CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
                'constraints' => $this->getPasswordConstraints($this->translator),
            ],
            'second_options' => [
                'label' => $this->translator->trans(
                    'security.change_password.password.repeat',
                    [],
                    CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
                'constraints' => $this->getPasswordConstraints($this->translator),
            ],
            'invalid_message' => $this->translator->trans(
                'security.change_password.password.not_match',
                [],
                CooolinhoSecurityBundle::TRANSLATION_DOMAIN
            ),
            'mapped' => $isMapped,
        ];
    }

    public function getPasswordConstraints(TranslatorInterface $translator): array
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
