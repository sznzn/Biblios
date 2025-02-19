<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('status', EnumType::class, [
                'class' => CommentStatus::class,
            ])
            ->add('content', TextareaType::class)
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
