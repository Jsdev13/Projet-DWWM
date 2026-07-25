<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeanceController extends AbstractController
{
    #[Route('/seances', name: 'app_seance_index')]
    public function index(): Response
    {
        // Jours de la semaine
        $jours = [
            ['nom' => 'Lun', 'numero' => 16, 'actif' => true],
            ['nom' => 'Mar', 'numero' => 17, 'actif' => false],
            ['nom' => 'Mer', 'numero' => 18, 'actif' => false],
            ['nom' => 'Jeu', 'numero' => 19, 'actif' => false],
            ['nom' => 'Ven', 'numero' => 20, 'actif' => false],
        ];

        // Liste des séances disponibles (données de démonstration)
        $seances = [
            [
                'id' => 1,
                'titre' => 'Hypertrophie',
                'categorie' => 'MUSCU',
                'duree' => '60 MIN',
                'places_restantes' => 3,
                'est_plein' => false,
                'heure_debut' => '08:00 H',
                'heure_fin' => '09:00 H',
                'coach' => 'Coach Alexis',
                'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&q=80&w=400'
            ],
            [
                'id' => 2,
                'titre' => 'Cardio HIT',
                'categorie' => 'CARDIO',
                'duree' => '45 MIN',
                'places_restantes' => 12,
                'est_plein' => false,
                'heure_debut' => '10:30 H',
                'heure_fin' => '11:15 H',
                'coach' => 'Coach Loïc',
                'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&q=80&w=400'
            ],
            [
                'id' => 3,
                'titre' => 'Boxe Anglaise',
                'categorie' => 'BOXE',
                'duree' => '30 MIN',
                'places_restantes' => 1,
                'est_plein' => false,
                'heure_debut' => '11:00 H',
                'heure_fin' => '11:30 H',
                'coach' => 'Coach Gaetan',
                'image' => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?auto=format&fit=crop&q=80&w=400'
            ],
            [
                'id' => 4,
                'titre' => 'Renforcement',
                'categorie' => 'MUSCU',
                'duree' => '50 MIN',
                'places_restantes' => 0,
                'est_plein' => true,
                'heure_debut' => '12:00 H',
                'heure_fin' => '12:50 H',
                'coach' => 'Coach Jonathan',
                'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=400'
            ],
        ];

        return $this->render('seance/index.html.twig', [
            'jours' => $jours,
            'seances' => $seances,
        ]);
    }
}