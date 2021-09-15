<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Entity\Material;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class LoanCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Loan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Uitlening')
            ->setEntityLabelInPlural('Uitleningen')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('loanedMaterial', 'Materiaal')->setRequired(true),
            AssociationField::new('reservation', 'Reservering')->setRequired(true),
            DateField::new('dateReturned', 'Ingeleverd op')->hideWhenCreating()
                ->setHelp('Wordt automatisch gevuld wanneer de staat bij inleveren wordt ingevuld.'),
            ChoiceField::new('returnedState', 'Staat bij inleveren')
                ->setChoices(array_combine(Material::STATES, Material::STATES))
                ->setHelp('Laat dit veld leeg wanneer deze nog niet is teruggelegd na uitlening.')
                ->hideWhenCreating(),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('reservation')->setLabel('Reservering'))
            ->add(EntityFilter::new('loanedMaterial')->setLabel('Materiaal'))
            ->add(ChoiceFilter::new('returnedState')
                ->setChoices(array_merge(['Nog niet ingeleverd' => null], array_combine(Material::STATES, Material::STATES)))->setLabel('Staat'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }

    public function configureActions(Actions $actions): Actions
    {
        $handInLoan = Action::new('handInLoan', 'Inleveren')
            ->linkToCrudAction('handInLoan');

        return $actions
            ->add(Crud::PAGE_INDEX, $handInLoan);
    }

    public function handInLoan(AdminContext $context)
    {
        /** @var Loan $loan */
        $loan = $context->getEntity()->getInstance();
        $loan->setReturnedState('Goed');
        $loan->setDateReturned(new DateTime());
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($loan);
        $manager->flush();

        $url = $this->adminUrlGenerator
            ->setController(LoanCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
