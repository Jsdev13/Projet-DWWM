<?php

namespace App\Controller;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach_index')]
    public function index(): Response
    {
        $coachs = [
            [
                'id' => 1,
                'nom' => 'LOIC LECLAIR',
                'specialite' => 'Boxe',
                'description' => 'Passionné de boxe depuis plusieurs années, Loïc partage son expérience avec tous les niveaux.',
                'photo' => 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'id' => 2,
                'nom' => 'JONATHAN SETIAO',
                'specialite' => 'Fitness & Bien-être',
                'description' => 'Passionné par le fitness et le bien-être, Jonathan accompagne ses clients dans l\'atteinte de leurs objectifs.',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=400',
            ],
        ];

        return $this->render('coach/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }

    #[Route('/coachs/{id}', name: 'app_coach_show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        if ($id === 2) {
            $coach = [
                'id' => 2,
                'nom' => 'JONATHAN SETIAO',
                'specialite' => 'COACH FITNESS & BIEN-ÊTRE',
                'description' => 'Passionné par le fitness, la musculation et le bien-être général, Jonathan accompagne ses clients...',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=800',
            ];
        } else {
            $coach = [
                'id' => 1,
                'nom' => 'LOIC LECLAIR',
                'specialite' => 'COACH BOXE ANGLAISE',
                'description' => 'Passionné de boxe depuis plusieurs années, Leclair partage son expérience...',
                'photo' => 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&q=80&w=800',
            ];
        }

        // Récupération dynamique des notes enregistrées en BDD pour ce coach
        $notes = $em->getRepository(Note::class)->findBy(['coachName' => $coach['nom']]);
        
        $total = 0;
        foreach ($notes as $note) {
            $total += $note->getValeur();
        }

        $count = count($notes);
        $avgRating = $count > 0 ? round($total / $count, 1) : 0.0;

        // Injection dynamique de la moyenne et du nombre d'avis
        $coach['averageRating'] = $avgRating;
        $coach['notesReceived'] = $notes;

        return $this->render('coach/show.html.twig', [
            'coach' => $coach,
        ]);
    }
}