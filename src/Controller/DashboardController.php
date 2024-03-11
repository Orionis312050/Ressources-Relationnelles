<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Repository\PostRepository;
use App\Repository\PostStatusRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\HelpEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class DashboardController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, UserRepository $userRepository, PostRepository $postRepository, SessionInterface $session, HelpEntityRepository $helpRepository, CategoryRepository $catRepo): Response
    {
        $stats_filter = "";
        $visits = $this->calculateVisits($session, $stats_filter);
        $userStats = $userRepository->findByStatsForLatestMonth();
        $postStats = $postRepository->findByStatsForLatestMonth();
        $statistiques = [
            'userStats' => $userStats,
            'postStats' => $postStats,
            'visits' => $visits
        ];

        $posts = $postRepository->findAll();
        $users = [];
        foreach ($posts as $post) {
            $user = $post->getUser();
            $users[$post->getId()] = $user;
        }
        $ressources = [
            'users' => $users,
            'posts' => $posts,
        ];

        $comptes = $userRepository->findAll();

        $questions = $helpRepository->findAll();

        $category = $catRepo->findAll();

        return $this->render('dashboard/index.html.twig', [
            'statistiques' => $statistiques,
            'ressources' => $ressources,
            'comptes' => $comptes,
            'questions' => $questions,
            'categories' => $category,
        ]);
    }

    #[Route('/dashboard/ajax', name: 'app_dashboard_ajax')]
    public function statistiques(Request $request, UserRepository $userRepository, PostRepository $postRepository, SessionInterface $session): JsonResponse
    {
        // Statistiques utilisateurs
        $userStats = [];
        $filter = $request->request->get('filter');
        switch ($filter) {
            case 'latest-month':
                $userStats = $userRepository->findByStatsForLatestMonth();
                break;
            case 'three-latest-month':
                $userStats = $userRepository->findByStatsForLastThreeMonths();
                break;
            case 'six-latest-month':
                $userStats = $userRepository->findByStatsForLastSixMonths();
                break;
            case 'latest-year':
                $userStats = $userRepository->findByStatsForLatestYear();
                break;
            case 'all':
                $userStats = $userRepository->findByAllStats();
                break;
            default:
                $userStats = $userRepository->findByStatsForLatestMonth();
                break;
        }

        // Statistiques publications
        $postStats = [];
        switch ($filter) {
            case 'latest-month':
                $postStats = $postRepository->findByStatsForLatestMonth();
                break;
            case 'three-latest-month':
                $postStats = $postRepository->findByStatsForLastThreeMonths();
                break;
            case 'six-latest-month':
                $postStats = $postRepository->findByStatsForLastSixMonths();
                break;
            case 'latest-year':
                $postStats = $postRepository->findByStatsForLatestYear();
                break;
            case 'all':
                $postStats = $postRepository->findByAllStats();
                break;
            default:
                $postStats = $postRepository->findByStatsForLatestMonth();
                break;
        }

        $visits = $this->calculateVisits($session, $filter);
        $stats = ['userStats' => $userStats, 'postStats' => $postStats, 'visits' => $visits];
        return new JsonResponse($stats);
    }

    public function calculateVisits(SessionInterface $session, string $filter): int
    {
        $visitDates = $session->get('visitDates', []);
        switch ($filter) {
            case 'latest-month':
                $interval = '-1 month';
                break;
            case 'three-latest-month':
                $interval = '-3 months';
                break;
            case 'six-latest-month':
                $interval = '-6 months';
                break;
            case 'latest-year':
                $interval = '-1 year';
                break;
            default:
                $interval = '-1 month';
                break;
        }

        $lastMonthDate = new \DateTime($interval);
        $visits = 0;
        foreach ($visitDates as $visitDate) {
            if (new \DateTime($visitDate) >= $lastMonthDate) {
                $visits++;
            }
        }
        return $visits;
    }

    #[Route('/dashboard/valid-post/{id}/{status}', name: 'app_dashboard_valid_ressource')]
    public function validRessource($id, $status, PostRepository $postRepo, PostStatusRepository $postStatusRepo): Response
    {
        $post = $postRepo->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$id);
        }
    
        $newStatusId = $status;
        $status = $postStatusRepo->find($newStatusId);
        $post->setStatus($status);
    
        $this->doctrine->getManager()->flush();
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard/role-user/{id}/{role}', name: 'app_dashboard_role_user')]
    public function roleUser($id, $role, UserRepository $userRepo, RoleRepository $roleRepo): Response
    {
        $user = $userRepo->find($id);
        $user->setRoles($role, $roleRepo);
    
        $this->doctrine->getManager()->flush();
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard/category/edit/{id}', name: 'app_dashboard_edit_category')]
    public function editCategory($id, CategoryRepository $catRepo, Request $request): Response
    {
        $formData = $request->request->get('name-cat');
        $category = $catRepo->find($id);
        $category->setName($formData);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard/category/add', name: 'app_dashboard_add_category')]
    public function addCategory(Request $request): Response
    {
        $formData = $request->request->get('name-cat');
        $category = new Category();
        $category->setName($formData);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard/category/delete/{id}', name: 'app_dashboard_delete_category')]
    public function deleteCategory($id, CategoryRepository $catRepo): Response
    {
        $category = $catRepo->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }
}
