<?php

namespace Cooolinho\SecurityBundle\Form;

use Cooolinho\SecurityBundle\CooolinhoSecurityBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => $this->translator->trans(
                                'security.change_password.message.not_blank',
                                [],
                                CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                            ),
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => $this->translator->trans(
                                'security.change_password.message.min_length',
                                [], CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                            ),
                            'max' => 4096,
                        ]),
                    ],
                    'label' => $this->translator->trans(
                        'security.change_password.password.new',
                        [],
                        CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                    ),
                ],
                'second_options' => [
                    'label' => $this->translator->trans(
                        'security.change_password.password.repeat',
                        [],
                        CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                    ),
                ],
                'invalid_message' => $this->translator->trans(
                    'security.change_password.password.not_match',
                    [],
                    CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
