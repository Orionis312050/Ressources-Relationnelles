<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Favorite;
use App\Entity\Repost;

use App\Repository\PostRepository;
use App\Repository\RepostRepository;

use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ImagesRepository;
use App\Repository\ParagraphesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\FavoriteRepository;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;





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
    public function post(PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, SessionInterface $session): \Symfony\Component\HttpFoundation\Response
    {
        $visitDate = new \DateTime();
        $session->set('visitDates', [$visitDate->format('Y-m-d H:i:s')]);


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
    public function catalogue(PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, RepostRepository $repostRepository): \Symfony\Component\HttpFoundation\Response
    {
                $images = $imagesRepository->findAll();
        $imagesPosts = [];
        $utilisateur = $this->getUser();
        $posts = $postRepository->findPostsByUser($utilisateur);

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


        $user = $this->getUser();

        $repostPosts = $repostRepository->findRepostPostsByUser($user);

        return $this->render('default/catalogue.html.twig', ['posts' => $posts,             'repostPosts' => $repostPosts,
        'utilisateur' => $utilisateur, 'images' => $imagesPosts]);
    }

    //quand je met la route de app_favorite avant ça fait l'inverse

// Controller


#[Route("/post/{id}", name: "app_post_actions", methods: ['POST'])]
public function postActions($id, Request $request, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, LikeRepository $likeRepository): \Symfony\Component\HttpFoundation\Response
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

    $action = $request->request->get('action');

    $existingLike = $this->entityManager->getRepository(Like::class)->findOneBy([
        'user' => $this->security->getUser(),
        'post' => $post,
    ]);

    $existingFavorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
        'user' => $this->security->getUser(),
        'post' => $post,
    ]);

    $existingRepost = $this->entityManager->getRepository(Repost::class)->findOneBy([
        'user' => $this->security->getUser(),
        'post' => $post,
    ]);

    if ($action === 'like') {
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
} elseif ($action === 'favorite') {
    if (!$existingFavorite) {

        $favorite = new Favorite();
        $favorite->setUser($this->security->getUser());
        $favorite->setPost($post);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        $existingFavorite=true;
    }else{
        $this->entityManager->remove($existingFavorite);
        $this->entityManager->flush();
        $existingFavorite=false;

    }
    } elseif ($action === 'repost') {
        if (!$existingRepost) {
    
            $repost = new Repost();
            $repost->setUser($this->security->getUser());
            $repost->setPost($post);
    
            $this->entityManager->persist($repost);
            $this->entityManager->flush();
    
            $existingRepost=true;
        }else{
            $this->entityManager->remove($existingRepost);
            $this->entityManager->flush();
            $existingRepost=false;
    
        }
        }

    // ... existing logic

    return $this->redirectToRoute('app_post_detail', ['id' => $id]);
}

#[Route("/post/{id}", name: "app_post_like", methods: ['POST'])]
public function postLike($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, LikeRepository $likeRepository, RepostRepository $repostRepository): \Symfony\Component\HttpFoundation\Response
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


    return $this->redirectToRoute('app_post_detail', ['id' => $id]);

}

#[Route("/post/{id}", name: "app_post_favorite", methods: ['POST'])]
public function postFavorite($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, FavoriteRepository $favoriteRepository): \Symfony\Component\HttpFoundation\Response
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

    $existingFavorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
        'user' => $this->security->getUser(),
        'post' => $post,
    ]);

    if (!$existingFavorite) {

        $favorite = new Favorite();
        $favorite->setUser($this->security->getUser());
        $favorite->setPost($post);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        $existingFavorite=true;
    }else{
        $this->entityManager->remove($existingFavorite);
        $this->entityManager->flush();
        $existingFavorite=false;

    }


    return $this->redirectToRoute('app_post_detail', ['id' => $id]);

}

#[Route("/post/{id}", name: "app_post_repost", methods: ['POST'])]
public function postRepost($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, RepostRepository $repostRepository): \Symfony\Component\HttpFoundation\Response
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

    $existingRepost = $this->entityManager->getRepository(Repost::class)->findOneBy([
        'user' => $this->security->getUser(),
        'post' => $post,
    ]);

    if (!$existingRepost) {

        $repost = new Repost();
        $repost->setUser($this->security->getUser());
        $repost->setPost($post);

        $this->entityManager->persist($repost);
        $this->entityManager->flush();

        $existingRepost=true;
    }else{
        $this->entityManager->remove($existingRepost);
        $this->entityManager->flush();
        $existingRepost=false;

    }


    return $this->redirectToRoute('app_post_detail', ['id' => $id]);

}

    #[Route("/post/{id}", name: "app_post_detail")]
    public function postDetail($id, PostRepository $postRepository, ImagesRepository $imagesRepository, ParagraphesRepository $paragraphesRepository, LikeRepository $likeRepository, FavoriteRepository $favoriteRepository, RepostRepository $repostyRepository): \Symfony\Component\HttpFoundation\Response
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

        $existingFavorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
            'user' => $this->security->getUser(),
            'post' => $post,
        ]);

        $existingRepost = $this->entityManager->getRepository(Post::class)->findOneBy([
            'user' => $this->security->getUser(),
            'post' => $post,
        ]);
        
    
        return $this->render('default/postDetail.html.twig', ['id' => $id, 'existingLike' => $existingLike, 'existingFavorite' => $existingFavorite, 'existingRepost' => $existingRepost,'post' => $post, 'images' => $images, 'paragraphes' => $paragraphes]);
    }
}