<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\Quantity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\AdminVoter;

#[IsGranted(AdminVoter::class)]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $routeBuilder->setController(RecipeCrudController::class)->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cook Chef');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Recettes', 'fa fa-utensils', Recipe::class);
        yield MenuItem::linkToCrud('Ingrédients', 'fa fa-carrot', Ingredient::class);
        yield MenuItem::linkToCrud('Quantités', 'fa fa-balance-scale', Quantity::class);
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'home');
    }
}
