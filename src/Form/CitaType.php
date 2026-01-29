<?php

namespace App\Form;

use App\Entity\Cita;
use App\Entity\Servicio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clienteNombre')
            ->add('telefono')
            ->add('matricula')
            ->add('modeloCoche')
            ->add('descripcionAveria')
            ->add('fechaDeseada')
            ->add('estado')
            ->add('fechaCreacion', null, [
                'widget' => 'single_text',
            ])
            ->add('servicio', EntityType::class, [
                'class' => Servicio::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cita::class,
        ]);
    }
}
