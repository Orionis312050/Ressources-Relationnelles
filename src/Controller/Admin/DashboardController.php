<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator){
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        $url = $this->adminUrlGenerator
            ->setController(PostCrudController::class)
            ->generateUrl();
        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    /*
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Application');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
    */

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bienvenue au Ressources Relationnelles');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::section('Nos voitures');

        //Les voitures
        yield MenuItem::subMenu('Voitures', 'fas fa-bars')->setSubItems([
            //Ajouter une voiture
            MenuItem::linkToCrud('Ajouter une voiture', 'fas fa-plus', Post::class)
                ->setAction(Crud::PAGE_NEW),
            //Aficher les voitures
            MenuItem::linkToCrud('Afficher les voitures', 'fas fa-eye', Post::class)
            // ->setAction(Crud::PAGE_NEW)
        ]);

        //Les utilistaeurs
        yield MenuItem::section('Les utilisateurs');

        yield MenuItem::subMenu('Users', 'fas fa-bars')->setSubItems([
            //Ajouter un utilisateur
            MenuItem::linkToCrud('Ajouter un utilisateur', 'fas fa-plus', User::class)
                ->setAction(Crud::PAGE_NEW),
            //Aficher les utilisateurs
            MenuItem::linkToCrud('Afficher les vutilisateur', 'fas fa-eye', User::class)
            // ->setAction(Crud::PAGE_NEW)
        ]);

        /*
        //Les reservations
        yield MenuItem::section('Les reservations');

        yield MenuItem::subMenu('Reservations', 'fas fa-bars')->setSubItems([
            //Ajouter une reservation
            MenuItem::linkToCrud('Ajouter une reservation', 'fas fa-plus', Reservation::class)
                ->setAction(Crud::PAGE_NEW),
            //Aficher les reservations
            MenuItem::linkToCrud('Afficher les vreservations', 'fas fa-eye', Reservation::class)
            // ->setAction(Crud::PAGE_NEW)
        ]);

        //Contacts
        yield MenuItem::section('Contacts');

        yield MenuItem::subMenu('Contacts', 'fas fa-bars')->setSubItems([
            //
            //MenuItem::linkToCrud('Afficher les messagess', 'fas fa-eye', Contact::class)
            // ->setAction(Crud::PAGE_NEW)
        ]);
        */

    }
}
