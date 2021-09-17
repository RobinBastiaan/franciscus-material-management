<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Entity\Material;
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
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $handInLoanBad = Action::new('handInLoanBad', 'Inleveren - Slechte staat')
            ->linkToCrudAction('handInLoanBad')
            ->setIcon('fa fa-times-circle')
            ->setCssClass('text-danger')
            ->displayIf(static function ($entity) {
                return empty($entity->getReturnedState());
            });
        $handInLoanMediocre = Action::new('handInLoanMediocre', 'Inleveren - Matige staat')
            ->linkToCrudAction('handInLoanMediocre')
            ->setIcon('fa fa-question-circle')
            ->setCssClass('text-warning')
            ->displayIf(static function ($entity) {
                return empty($entity->getReturnedState());
            });
        $handInLoanGood = Action::new('handInLoanGood', 'Inleveren - Goede staat')
            ->linkToCrudAction('handInLoanGood')
            ->setIcon('fa fa-check-circle')
            ->setCssClass('text-success')
            ->displayIf(static function ($entity) {
                return empty($entity->getReturnedState());
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $handInLoanBad)
            ->add(Crud::PAGE_INDEX, $handInLoanMediocre)
            ->add(Crud::PAGE_INDEX, $handInLoanGood)
            ->reorder(Crud::PAGE_INDEX, [Action::EDIT, Action::DELETE]);
    }

    public function handInLoanBad(AdminContext $context): RedirectResponse
    {
        return $this->handInLoan($context, 'Slecht');
    }

    public function handInLoanMediocre(AdminContext $context): RedirectResponse
    {
        return $this->handInLoan($context, 'Matig');
    }

    public function handInLoanGood(AdminContext $context): RedirectResponse
    {
        return $this->handInLoan($context, 'Goed');
    }

    public function handInLoan(AdminContext $context, string $state): RedirectResponse
    {
        /** @var Loan $loan */
        $loan = $context->getEntity()->getInstance();
        $loan->handIn($state);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($loan);
        $manager->flush();

        $url = $this->adminUrlGenerator
            ->setController(__CLASS__)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
