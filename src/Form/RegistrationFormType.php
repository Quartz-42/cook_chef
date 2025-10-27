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
                'label' => 'Email',
            ])

            ->addDependent('plainPassword', ['email'], function (DependentField $field, $email) {
            if (!$email) {
                return;
            }

            // Validation de l'email
            $violations = $this->validator->validate($email, [
                new NotBlank(['message' => 'Please enter an email']),
                new Email(['message' => 'The email "{{ value }}" is not a valid email.']),
            ]);
            
            // Si erreurs de validation, ne pas afficher le champ suivant
            if (count($violations) > 0) {
                return;
            }

            // Ajouter le champ password si l'email est valide
            $field->add(PasswordType::class, [
                //attribut magique pour pas que le champ password soit a blank apres un submit
                'always_empty' => false,
                'label' => 'Password',
                'mapped' => false,
                'toggle' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
        })

            ->addDependent('agreeTerms', ['plainPassword'], function (DependentField $field, $plainPassword) {
                if (!$plainPassword) {
                    return;
                }

                // Validation du mot de passe
                $violations = $this->validator->validate($plainPassword, [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]);
                
                // Si erreurs de validation, ne pas afficher le champ suivant
                if (count($violations) > 0) {
                    return;
                }

                // Ajouter la checkbox si le password est valide
                $field->add(CheckboxType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'You should agree to our terms.',
                        ]),
                    ],
                ]);
            })

             ->addDependent('submit', ['agreeTerms'], function (DependentField $field, $agreeTerms) {
                if (!$agreeTerms) {
                    return;
                }

                // Validation que les termes sont acceptés (checkbox cochée)
                $violations = $this->validator->validate($agreeTerms, [
                    new IsTrue(['message' => 'You should agree to our terms.']),
                ]);
                
                // Si erreurs de validation, ne pas afficher le bouton
                if (count($violations) > 0) {
                    return;
                }

                $field->add(SubmitType::class, [
                      'attr' => ['class' => 'btn btn-primary'],
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
