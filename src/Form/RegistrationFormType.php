<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationFormType extends AbstractType
{

    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])

            ->add('plainPassword', PasswordType::class, [
                    'label' => 'Password',
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    // 'attr' => ['autocomplete' => 'new-password'],
                    'toggle' => true,
                     //on laisse la contrainte ici car on gere pas le password de l'entité User mais
                    //un autre champ non mappé qu'on transforme apres.....
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])

                // ->add('agreeTerms', CheckboxType::class, [
                //     'mapped' => false,
                //     'constraints' => [
                //         new IsTrue([
                //             'message' => 'You should agree to our terms.',
                //         ]),
                //     ],
                // ])

                ->add('submit', SubmitType::class, [
                    'label' => 'Register',
                    'attr' => ['class' => 'btn btn-primary'],
                ]);

                // ->addDependent('submit', ['agreeTerms'], function (DependentField $field, $agreeTerms) {
                // if (!$agreeTerms) {
                //     return;
                // }

                // // Validation que les termes sont acceptés (checkbox cochée)
                // $violations = $this->validator->validate($agreeTerms, [
                //     new IsTrue(['message' => 'You should agree to our terms.']),
                // ]);
                // if (count($violations) > 0) {
                //     return;
                // }

                // $field->add(SubmitType::class, [
                //     'label' => 'Register',
                //     'attr' => ['class' => 'btn btn-primary'],
                // ]);
            // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
