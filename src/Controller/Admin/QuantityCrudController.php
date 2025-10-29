<?php

namespace App\Controller\Admin;

use App\Entity\Quantity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\AdminVoter;

#[IsGranted(AdminVoter::class)]
class QuantityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Quantity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Quantité')
            ->setEntityLabelInPlural('Quantités')
            ->setSearchFields(['unit', 'ingredient.name', 'recipe.title'])
            ->setDefaultSort(['unit' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield NumberField::new('quantity', 'Quantité');
        yield TextField::new('unit', 'Unité');
        yield AssociationField::new('ingredient', 'Ingrédient');
        yield AssociationField::new('recipe', 'Recette');
    }
}
