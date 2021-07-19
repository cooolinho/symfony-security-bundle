<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Cooolinho\Bundle\SecurityBundle\Form\Traits\PlainPasswordTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordFormType extends AbstractType
{
    use PlainPasswordTrait;

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
                    'constraints' => $this->getPasswordConstraints($builder, $this->translator),
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
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
