<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'attr' => ['class' => 'form-control', 'placeholder' => 'ejemplo@correo.com']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Contraseña',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Mínimo 6 caracteres']
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Mínimo 6 caracteres']
                ],
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'constraints' => [
                    new NotBlank(message: 'Por favor, introduce una contraseña'),
                    new Length(
                        min: 6,
                        minMessage: 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
