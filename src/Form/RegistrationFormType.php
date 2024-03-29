<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Cooolinho\Bundle\SecurityBundle\Entity\AbstractUser;
use Cooolinho\Bundle\SecurityBundle\Form\Traits\RepeatedPasswordFormTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    use RepeatedPasswordFormTypeTrait;

    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email');
        $builder->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => $this->translator->trans(
                'security.registration.message.terms.label',
                [],
                CooolinhoSecurityBundle::TRANSLATION_DOMAIN
            ),
            'constraints' => [
                new IsTrue([
                    'message' => $this->translator->trans(
                        'security.registration.message.terms.error',
                        [],
                        CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                    ),
                ]),
            ],
        ]);

        $this->addRepeatedPasswordField($builder, $this->translator);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Register',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbstractUser::class,
        ]);
    }
}
