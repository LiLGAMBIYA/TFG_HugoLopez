<?php

namespace App\Form;

use App\Entity\Cita;
use App\Entity\Servicio;
use App\Entity\Usuario;
use App\Entity\Vehiculo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class CitaType extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');
        $user = $this->security->getUser();

        if ($isAdmin) {
            $builder->add('cliente', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'email',
                'label' => 'Cliente',
            ]);
        }

        $builder
            ->add('vehiculo', EntityType::class, [
                'class' => Vehiculo::class,
                'query_builder' => function (EntityRepository $er) use ($isAdmin, $user) {
                    $qb = $er->createQueryBuilder('v');
                    if (!$isAdmin) {
                        $qb->where('v.propietario = :user')
                           ->setParameter('user', $user);
                    }
                    return $qb;
                },
                'choice_label' => function (Vehiculo $vehiculo) {
                    return $vehiculo->getMatricula() . ' - ' . $vehiculo->getMarca() . ' ' . $vehiculo->getModelo();
                },
                'label' => 'Vehículo',
            ])
            ->add('servicio', EntityType::class, [
                'class' => Servicio::class,
                'choice_label' => 'nombre',
                'label' => 'Servicio',
            ])
            ->add('descripcionAveria', TextareaType::class, [
                'label' => 'Descripción de la Avería',
            ])
            ->add('fechaDeseada', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Deseada',
            ])
        ;

        if ($isAdmin) {
            $builder->add('estado', ChoiceType::class, [
                'choices' => [
                    'Pendiente' => 'Pendiente',
                    'Confirmada' => 'Confirmada',
                    'Realizada' => 'Realizada',
                    'Cancelada' => 'Cancelada',
                ],
                'label' => 'Estado',
            ]);

            $builder->add('operario', EntityType::class, [
                'class' => Usuario::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_ADMIN"%');
                },
                'choice_label' => 'email',
                'label' => 'Operario Asignado',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cita::class,
        ]);
    }
}
