<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Posts;

class PostsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;

    }

    #[Route('/lista', name: 'app_posts')]
    public function index(EntityManagerInterface $em): Response
    {
        $repository=$this->em->getRepository(Posts::class);
        $posts=$repository->findAll();
        return $this->render('index.html.twig',[
            'posts'=>$posts
        ]);
    }

    #[Route('/lista/delete/{id}', methods:['GET','DELETE'], name: 'delete_post')]
    public function delete($id): Response
    {
        $repository=$this->em->getRepository(Posts::class);
        $post=$repository->find($id);
        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute('app_posts');

    }
}
