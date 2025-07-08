<?php

namespace App\Form;

use App\Entity\Estudiante;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstudianteFormType extends AbstractType
{
    private const INPUT_STYLE = 'form-control';
    private const LABEL_STYLE = 'form-label mt-3 fw-bold text-dark';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identidad', IntegerType::class, [
                'label' => 'Número de Identidad:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_identidad',
                    'placeholder' => 'Ingresa el número de identidad',
                ],
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_nombre',
                    'placeholder' => 'Escribe el nombre',
                ],
            ])
            ->add('salon', TextType::class, [
                'label' => 'Salón:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_salon',
                    'placeholder' => 'Ej: 9A, 10B...',
                ],
            ])
            ->add('acudiente', TextType::class, [
                'label' => 'Acudiente:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_acudiente',
                    'placeholder' => 'Nombre del acudiente',
                ],
            ])
            ->add('edad', IntegerType::class, [
                'label' => 'Edad:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_edad',
                    'placeholder' => 'Edad del estudiante',
                ],
            ])
            ->add('genero', TextType::class, [
                'label' => 'Género:',
                'required' => true,
                'label_attr' => ['class' => self::LABEL_STYLE],
                'attr' => [
                    'class' => self::INPUT_STYLE,
                    'id' => 'user_form_genero',
                    'placeholder' => 'Masculino, Femenino, Otro...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estudiante::class,
            'is_edit' => false,
        ]);
    }
}
