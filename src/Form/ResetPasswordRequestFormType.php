<?php

namespace Cooolinho\Bundle\SecurityBundle\Form;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans(
                    'security.user.email',
                    [],
                    CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                ),
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans(
                            'security.reset_password.message.not_blank',
                            [],
                            CooolinhoSecurityBundle::TRANSLATION_DOMAIN
                        ),
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
