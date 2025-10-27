<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
                'empty_data' => '',
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'label' => 'Slug (autocompleted)',
                'attr' => ['disabled' => 'disabled'],
                'constraints' => new Sequentially([
                    new Length(['min' => 5, 'max' => 100]),
                    new Regex(['pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'message' => 'Le slug doit être en minuscules, sans espaces et peut contenir des tirets.']),
                ]),
            ])
            ->add('thumbnailFile', FileType::class, [
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Etapes',
                'empty_data' => '',
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée',
            ])
            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-controller' => 'form-collection',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug']) && !empty($data['title'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title'])->toString());
            $event->setData($data);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'validation_groups' => ['Default', 'Extra'],
        ]);
    }
}
