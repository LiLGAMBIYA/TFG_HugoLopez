<?php

namespace App\Form;

use App\Entity\Usuario;
use App\Entity\Vehiculo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class VehiculoType extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricula')
            ->add('marca')
            ->add('modelo')
            ->add('vin')
        ;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('propietario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'email',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehiculo::class,
        ]);
    }
}
