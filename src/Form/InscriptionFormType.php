<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('POST');

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'xyz@email.xyz'],
                'constraints' => [new Assert\NotBlank() , new Assert\Email(['message' => 'Email incorrect'])]
            ])

            ->add('pseudo', TextType::class,
                ['label' => 'pseudo', 'attr' => ['placeholder' => 'John Doe']], 
                ['constraints' => [new Assert\NotBlank(), new Assert\Length([
                    "min" => 3, "minMessage" => "Your pseudo must at least be 3 characters long.", 
                    "max" => 50, "maxMessage" => "Your pseudo is too long (maximum is 50 !)"])]]
                );

                $builder->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ])

            ->add('submit', SubmitType::class);
    }

}
