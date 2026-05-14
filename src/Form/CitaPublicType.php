<?php

namespace App\Form;

use App\Entity\Cita;
use App\Entity\Servicio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitaPublicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clienteNombre', TextType::class, [
                'label' => 'Nombre y Apellidos',
                'required' => true,
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono de Contacto',
                'required' => true,
            ])
            ->add('matricula', TextType::class, [
                'mapped' => false,
                'label' => 'Matrícula del Vehículo',
                'required' => true,
            ])
            ->add('marca', TextType::class, [
                'mapped' => false,
                'label' => 'Marca del Vehículo (Ej. Audi)',
                'required' => true,
            ])
            ->add('modelo', TextType::class, [
                'mapped' => false,
                'label' => 'Modelo del Vehículo (Ej. A3)',
                'required' => true,
            ])
            ->add('servicio', EntityType::class, [
                'class' => Servicio::class,
                'choice_label' => 'nombre',
                'label' => 'Servicio Deseado',
            ])
            ->add('descripcionAveria', TextareaType::class, [
                'label' => 'Descripción del Problema',
                'required' => true,
            ])
            ->add('fechaDeseada', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Deseada',
                'required' => true,
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
