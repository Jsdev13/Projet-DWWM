<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}