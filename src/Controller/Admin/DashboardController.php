<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Entity\Location;
use App\Entity\Material;
use App\Entity\Note;
use App\Entity\Reservation;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    private MaterialRepository $materialRepository;

    public function __construct(MaterialRepository $materialRepository, EntityManagerInterface $entityManager)
    {
        $this->materialRepository = $materialRepository;

        $entityManager->getFilters()->disable('softdeleteable');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $materialTotals = $this->materialRepository->totals();

        return $this->render('bundles/EasyAdminBundle/default/dashboard.html.twig', [
            'material_totals' => $materialTotals,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Franciscus Material Management')
            ->setFaviconPath('/build/logo.svg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Toegang');
        yield MenuItem::linkToCrud('Gebruikers', 'fas fa-user', User::class);

        yield MenuItem::section('Materiaal');
        yield MenuItem::linkToCrud('Materiaal', 'fas fa-boxes', Material::class);
        yield MenuItem::linkToCrud('Notities', 'fas fa-pen', Note::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Locaties', 'fas fa-map-marker-alt', Location::class);

        yield MenuItem::section('Uitlenen');
        yield MenuItem::linkToCrud('Reserveringen', 'fas fa-campground', Reservation::class);
        yield MenuItem::linkToCrud('Uitleningen', 'fas fa-trailer', Loan::class);
    }

    /**
     * @param User $user
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getEmail())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('Terug naar applicatie', 'fa fa-back', 'homepage'),
            ]);
    }
}
