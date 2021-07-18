<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Cooolinho\Bundle\SecurityBundle\Form\Traits\PlainPasswordTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
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
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans(
                            'security.registration.message.terms',
                            [],
                            CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                        ),
                    ]),
                ],
                'translation_domain' => 'security'
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => $this->getPasswordConstraints($builder, $this->translator),
                'translation_domain' => 'security',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
