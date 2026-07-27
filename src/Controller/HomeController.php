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
        // Récupère la séance de boxe et de cardio directement depuis PostgreSQL
        $seanceBoxe = $seanceRepo->findOneBy(['name' => 'Boxe : Shadow-boxing']);
        $seanceCardio = $seanceRepo->findOneBy(['name' => 'Cardio : Endurance']);

        return $this->render('home/index.html.twig', [
            'activites' => [
                [
                    'id' => $seanceBoxe ? $seanceBoxe->getId() : 2,
                    'titre' => 'Entraînement Boxe', 
                    'image' => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?auto=format&fit=crop&q=80&w=400', 
                    'slug' => 'boxe'
                ],
                [
                    'id' => $seanceCardio ? $seanceCardio->getId() : 3, 
                    'titre' => 'Entraînement Cardio', 
                    'image' => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?auto=format&fit=crop&q=80&w=400', 
                    'slug' => 'cardio'
                ],
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