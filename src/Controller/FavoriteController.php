<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ...

use App\Repository\FavoriteRepository;

class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        // Assuming you have a user entity and you can get the current user
        $user = $this->getUser();

        // Retrieve the favorite posts for the current user
        $favoritePosts = $favoriteRepository->findFavoritePostsByUser($user);

        return $this->render('favorite/index.html.twig', [
            'controller_name' => 'FavoriteController',
            'favoritePosts' => $favoritePosts,
        ]);
    }
}