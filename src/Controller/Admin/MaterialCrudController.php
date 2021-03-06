<?php

namespace App\Controller\Admin;

use App\Entity\Material;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Material::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Materiaal')
            ->setEntityLabelInPlural('Materialen')
            ->setDefaultSort(['name' => 'ASC'])
            ->overrideTemplate('crud/detail', 'bundles/EasyAdminBundle/crud/detail-material.html.twig')
            ->overrideTemplate('crud/new', 'bundles/EasyAdminBundle/crud/new-material.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $locationField = ArrayField::new('location', 'Opslaglocatie');
//            $tagField = ArrayField::new('tags');
            $imageField = ImageField::new('image', 'Afbeelding')->setBasePath('/images/materials');
            $receiptField = ImageField::new('receipt', 'Bonnetje')->setBasePath('/images/receipts');
        } else {
            $locationField = AssociationField::new('location', 'Opslaglocatie');
//            $tagField = AssociationField::new('tags')->autocomplete();
            $imageField = TextField::new('imageFile', 'Afbeelding')->setFormType(VichImageType::class);
            $receiptField = TextField::new('receiptFile', 'Bonnetje')->setFormType(VichImageType::class);
        }

        return [
            NumberField::new('amount', 'Aantal'),
            $imageField,
            TextField::new('name', 'Naam')->setCssClass('js-row-action'),
            AssociationField::new('category', 'Categorie'),
            TextareaField::new('description', 'Korte omschrijving'),
            TextEditorField::new('information', 'Uitgebreide informatie'),
            ChoiceField::new('state', 'Staat')->setChoices(array_combine(Material::STATES, Material::STATES)),
            DateField::new('dateBought', 'Koopdatum'),
            MoneyField::new('value', 'Aankoopwaarde')->setCurrency('EUR')->setStoredAsCents(false)->setHelp('Wat dit materiaal heeft gekost of zou kosten als dit materiaal niet gesponsord zou zijn.'),
            MoneyField::new('currentValue', 'Huidige waarde')->setCurrency('EUR')->setStoredAsCents(false)->hideOnForm(),
            MoneyField::new('residualValue', 'Restwaarde')->setCurrency('EUR')->setStoredAsCents(false),
            TextField::new('manufacturer', 'Fabrikant'),
            NumberField::new('depreciationYears', 'Afschrijvingsjaren')->setHelp('Laat dit veld leeg wanneer dit materiaal niet vervangen hoeft te worden.'),
            $locationField,
//            $tagField->setSortable(false),
            ArrayField::new('notes', 'Notities')->onlyOnDetail(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
            $receiptField,
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name')->setLabel('Naam'))
            ->add(TextFilter::new('category')->setLabel('Categorie'))
            ->add(ChoiceFilter::new('state')
                ->setChoices(array_combine(Material::STATES, Material::STATES))->setLabel('Staat'))
            ->add(TextFilter::new('location')->setLabel('Opslaglocatie'))
            ->add(NumericFilter::new('value')->setLabel('Aankoopwaarde'))
            ->add(EntityFilter::new('tags'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }

    public function configureActions(Actions $actions): Actions
    {
        $details = Action::new('detail', 'Details bekijken')
            ->linkToCrudAction('detail')
            ->setIcon('fa fa-info');

        return $actions
            ->add(Crud::PAGE_INDEX, $details)
            ->reorder(Crud::PAGE_INDEX, [Action::EDIT, Action::DELETE]);
    }
}
