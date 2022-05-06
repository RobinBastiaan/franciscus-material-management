<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Categorie')
            ->setEntityLabelInPlural('Categorieën')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam')->setCssClass('js-row-action'),
            TextField::new('icon', 'Icoon'),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Geschreven')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('name')->setLabel('Naam'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }
}
