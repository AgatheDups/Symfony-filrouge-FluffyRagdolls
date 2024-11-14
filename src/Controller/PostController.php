<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/forum', name: 'app_post_index')]
    public function index(PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        $posts = $postRepository->findAll();
        $postsWithCommentCount = [];
        foreach ($posts as $post) {
            $postsWithCommentCount[] = [
                'post' => $post,
                'commentCount' => $commentRepository->countCommentsByPost($post),
            ];
        }
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
        return $this->render('post/index.html.twig', [
            'postsWithCommentCount' => $postsWithCommentCount,
        ]);
    }

    #[Route('/forum/post/{id}', name: 'app_post_show', requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request): Response
    {
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Post non trouvé');
        }
        
        $comments = $this->entityManager->getRepository(Comment::class)->findBy(['post' => $post]);
        
        $comment = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $id]);
        }
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    #[Route('/forum/post/new', name: 'app_post_create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new DateTimeImmutable());
            $post->setUpdatedAt(new DateTimeImmutable());
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/index-create.html.twig', [
            'PostForm' => $form->createView(),
        ]);
    }
}
