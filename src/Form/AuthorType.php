<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
                ])
                ->add('dateOfDeath', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false
                

            ])
            ->add('nationality', TextType::class, [
                'required' => false
                ])
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false
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
