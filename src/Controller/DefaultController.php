<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ImagesRepository;
use App\Repository\ParagraphesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Symfony\Component\Security\Core\Security;



class DefaultController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/default', name: 'app_default')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }

//    #[Route("/", name:"app_homepage")]
//    public function indexs()
//    {
//        //$utilisateur = $this->getUser();
//
//
//        return $this->render('default/index.html.twig', /*[
//            'utilisateur' => $utilisateur,
//        ]*/);
//    }

    #[Route("/base", name:"app_base")]
    public function base(PostRepository $postRepository): \Symfony\Component\HttpFoundation\Response
    {
        $posts = $postRepository->findAll();
        $utilisateur = $this->getUser();
        return $this->render('base.html.twig', ['posts' => $posts, 'utilisateur' => $utilisateur]);
    }

    #[Route("/", name:"app_homepage")]
    public function post(PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository): \Symfony\Component\HttpFoundation\Response
    {
        $posts = $postRepository->findAll();
        $images = $imagesRepository->findAll();
        $imagesPosts = [];
        $utilisateur = $this->getUser();
        foreach ($posts as $post) {
            $post_id = $post->getId();
            $imagesPostId = [];
            foreach ($images as $image) {
                if ($post_id == $image->getPostId()->getId()) {
                    $imagesPostId[] = $image;
                }
            }
            $imagesPosts[$post_id] = $imagesPostId;
            $catId = $post->getType();
        }
        return $this->render('default/index.html.twig', ['posts' => $posts, 'utilisateur' => $utilisateur, 'images' => $imagesPosts]);
    }

    #[Route("/catalogue", name:"app_catalogue")]
    public function catalogue(PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository): \Symfony\Component\HttpFoundation\Response
    {
        $posts = $postRepository->findAll();
        $images = $imagesRepository->findAll();
        $imagesPosts = [];
        $utilisateur = $this->getUser();
        foreach ($posts as $post) {
            $post_id = $post->getId();
            $imagesPostId = [];
            foreach ($images as $image) {
                if ($post_id == $image->getPostId()->getId()) {
                    $imagesPostId[] = $image;
                }
            }
            $imagesPosts[$post_id] = $imagesPostId;
        }
        return $this->render('default/catalogue.html.twig', ['posts' => $posts, 'utilisateur' => $utilisateur, 'images' => $imagesPosts]);
    }

    //ça marche bien mais ça s'active même quand je refresh la page

    #[Route("/post/{id}", name: "app_post_like", methods: ['POST'])]
    public function postLike($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, LikeRepository $likeRepository): \Symfony\Component\HttpFoundation\Response
    {

        
        $post = $postRepository->find($id);
        $images = $imagesRepository->findBy(['post_id' => $id]);
        $paragraphes = $paragraphesRepository->findBy(['post_id' => $id]);
    
        if (!$this->security->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $post = $this->entityManager->getRepository(Post::class)->find($id);
    
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
    
        $existingLike = $this->entityManager->getRepository(Like::class)->findOneBy([
            'user' => $this->security->getUser(),
            'post' => $post,
        ]);
    
        if (!$existingLike) {

            $like = new Like();
            $like->setUser($this->security->getUser());
            $like->setPost($post);
    
            $this->entityManager->persist($like);
            $this->entityManager->flush();

            $existingLike=true;
        }else{
            $this->entityManager->remove($existingLike);
            $this->entityManager->flush();
            $existingLike=false;

        }
    
        return $this->redirectToRoute('app_post_like', ['id' => $id]);

    }
    

    #[Route("/post/{id}", name: "app_post_detail")]
    public function postDetail($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, LikeRepository $likeRepository): \Symfony\Component\HttpFoundation\Response
    {
        $post = $postRepository->find($id);
        $images = $imagesRepository->findBy(['post_id' => $id]);
        $paragraphes = $paragraphesRepository->findBy(['post_id' => $id]);
    
        if (!$this->security->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $post = $this->entityManager->getRepository(Post::class)->find($id);
    
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }


 
        $existingLike = $this->entityManager->getRepository(Like::class)->findOneBy([
            'user' => $this->security->getUser(),
            'post' => $post,
        ]);
        
    
        return $this->render('default/postDetail.html.twig', ['id' => $id, 'existingLike' => $existingLike, 'post' => $post, 'images' => $images, 'paragraphes' => $paragraphes]);
    }

    

    



}
