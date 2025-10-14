<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\DTO\ContactDTO;
use function Symfony\Component\Translation\t;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => t('contactForm.name'),
                'empty_data' => '',
            ])
            ->add('mail', EmailType::class, [
                'label' => t('contactForm.mail'),
                'empty_data' => '',
            ])
            ->add('message', TextareaType::class, [
                'label' => t('contactForm.message'),
                'empty_data' => '',
            ])
            ->add('submit', SubmitType::class, [
                'label' => t('contactForm.submit')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
