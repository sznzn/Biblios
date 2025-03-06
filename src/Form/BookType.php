<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use App\Enum\BookStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'required' => true,
            ])
            ->add('isbn', TextType::class,[
                'required' => true,
            ])
            ->add('cover', UrlType::class, [
                'default_protocol' => 'https', 
            ])
            ->add('editedAt', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'html5' => true,
            ])
            ->add('plot', TextareaType::class)
            ->add('pageNumber', NumberType::class)
            ->add('status', EnumType::class, [
                'class' => BookStatus::class,
            ])
            
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
                
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'choice_label' => 'name',
            ])
            ->add('certification', CheckboxType::class, [
                'mapped' => false,
                'required' => true,
                'label' => "Je suis certifie l'exactitude des informations fournies",
                'constraints' => [
                    new Assert\IsTrue(message: "Vous devez cocher la case pour ajouter un livre")
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            
        ]);
    }
}
