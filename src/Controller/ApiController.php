<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private $postGenerator;

    public function __construct(PostService $postGenerator)
    {
        $this->postGenerator = $postGenerator;
    }

    /**
     * @Route("/posts", methods={"GET"})
     */
    public function getAll(): Response
    {
        return new JsonResponse($this->postGenerator->getAllPost());
    }

    /**
     * @Route("/posts/{id}", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getOne(int $id): Response
    {
        return new JsonResponse($this->postGenerator->getPost($id));
    }
}
