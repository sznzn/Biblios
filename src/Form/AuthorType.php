<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Book;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                // 'format' => 'dd/MM/yyyy',
                'years' => range(date('Y') - 120, date('Y')),
                'label' => 'Date de naissance',
                'required' => true,
                'html5' => true,
                ])

            ->add('dateOfDeath', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                // 'format' => 'dd/MM/yyyy',
                'years' => range(date('Y') - 120, date('Y')),
                'label' => 'Date de décès',
                'required' => false,
                'html5' => true,
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Nationalité',
                'required' => false,
            ])
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Livres',
                'required' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
