<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class EditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('avatar', FileType::class, [
            'label' => 'Ajouter une image de profil',
            'mapped' => false, // indique que le champ n'est pas lié directement à l'entité User
            'required' => false,
            // 'constraints' => [
            //     new File([
            //         'mimeTypes' => [
            //             'application/png',
            //             'application/jpg',
            //             'application/webp',
            //         ],
            //         'mimeTypesMessage' => 'Please upload a valid PNG/JPG/WEBP document',
            //     ])
            // ]
        ]);
        
        $builder->add('pseudo', TextType::class, [
            'label' => 'Pseudo',
            'required' => 'false',
            'attr' => ['placeholder' => 'John Doe'],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 6,
                    'minMessage' => "Your pseudo must be at least 6 characters long.",
                    'max' => 50,
                    'maxMessage' => "Your pseudo is too long (maximum is 50 characters)."
                ])
            ]
        ]);
        
        $builder->add('emploi', TextType::class, [
            'label' => 'Emploi',
            'attr' => ['placeholder' => 'Equipier polyvalent'],
            'constraints' => [
                new Assert\Length([
                    'min' => 6,
                    'minMessage' => "Can't add a job that's less than 6 characters.",
                    'max' => 50,
                    'maxMessage' => "You've reached the maximum amount of characters."
                ])
            ]
        ]);
        
        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => 'false',
            'attr' => ['placeholder' => 'Tell us more about you...'],
            'constraints' => [
                new Assert\Length([
                    'max' => 500,
                    'maxMessage' => "You've reached the maximum amount of characters."
                ])
            ]
        ])
                ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
