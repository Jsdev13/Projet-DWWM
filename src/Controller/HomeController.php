<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use App\Repository\CoachRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SeanceRepository $seanceRepo, CoachRepository $coachRepo): Response
    {
        return $this->render('home/index.html.twig', [
            // Données dynamiques
            'activites' => [
                ['titre' => 'Entraînement Boxe', 'image' => 'boxe.jpg', 'slug' => 'boxe'],
                ['titre' => 'Entraînement Cardio', 'image' => 'cardio.jpg', 'slug' => 'cardio'],
            ],
            'coach' => [
                'nom' => 'Loïc Leclair',
                'role' => 'Coach',
                'photo' => 'loic.jpg',
                'bio' => 'Passionné de boxe depuis plusieurs années, Loïc partage son expérience avec tous les niveaux, du débutant au confirmé.'
            ],
            'adresse' => '17 Allée, Jean Jaurès, 31000 Toulouse'
        ]);
    }
}
