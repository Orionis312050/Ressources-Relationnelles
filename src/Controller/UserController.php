<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\User;

class UserController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // Méthode pour personnaliser les actions de liste
    // Par exemple, pour filtrer les utilisateurs actifs uniquement
    public function index(AdminContext $context)
    {
        $response = parent::index($context);

        // Personnalisez ici la liste des utilisateurs si nécessaire

        return $response;
    }

    // Méthode pour personnaliser l'affichage d'un utilisateur spécifique
    public function show(AdminContext $context)
    {
        $response = parent::show($context);

        // Personnalisez ici l'affichage de l'utilisateur si nécessaire

        return $response;
    }

    // Méthode pour personnaliser le formulaire d'édition d'un utilisateur
    public function edit(AdminContext $context)
    {
        $response = parent::edit($context);

        // Personnalisez ici le formulaire d'édition de l'utilisateur si nécessaire

        return $response;
    }

    // Méthode pour personnaliser l'action de suppression d'un utilisateur
    public function delete(AdminContext $context)
    {
        $response = parent::delete($context);

        // Ajoutez ici des actions supplémentaires après la suppression de l'utilisateur si nécessaire

        return $response;
    }

    #[Route('/user', name: 'app_user')]
    public function monCompte(): Response
    {
        // Récupérer l'utilisateur connecté
        $utilisateur = $this->getUser();

        return $this->render('user/user.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    public function register(Request $request): Response
    {
        // Handle user registration form submission

        // Create a new User entity
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        // ... other code to handle form submission and persist the user

        return $this->redirectToRoute('registration_success');
    }

    // Ajoutez d'autres méthodes pour gérer les actions CRUD supplémentaires si nécessaire
}
/*
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
*/