<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\AdminVoter;

#[IsGranted(AdminVoter::class)]
class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Recette')
            ->setEntityLabelInPlural('Recettes')
            ->setSearchFields(['title', 'slug', 'content'])
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre');
        yield SlugField::new('slug')->setTargetFieldName('title')->onlyOnForms();
        yield TextareaField::new('content', 'Contenu');
        yield IntegerField::new('duration', 'Durée (min)');

        yield ImageField::new('thumbnail', 'Miniature')
            ->setBasePath('/upload/recettes/images')
            ->setUploadDir('public/upload/recettes/images')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false);

        yield AssociationField::new('author', 'Auteur')
            ->formatValue(static function ($value) {
                // Sécurise le cas où l'auteur est null ou un type inattendu
                if ($value === null) {
                    return '—';
                }
                if (is_object($value) && method_exists($value, 'getEmail')) {
                    return $value->getEmail();
                }
                if (is_string($value)) {
                    return $value;
                }
                return '—';
            })
            ->hideOnForm();
        yield DateTimeField::new('createdAt', 'Créée le')->hideOnForm();
        yield DateTimeField::new('updatedAt', 'Modifiée le')->hideOnForm();
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Recipe) {
            parent::persistEntity($em, $entityInstance);
            return;
        }

        if (null === $entityInstance->getCreatedAt()) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable());
        }
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());

        if (null === $entityInstance->getAuthor() && $this->getUser() && method_exists($this->getUser(), 'getId')) {
            $entityInstance->setAuthor($this->getUser());
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Recipe) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }

        parent::updateEntity($em, $entityInstance);
    }
}
