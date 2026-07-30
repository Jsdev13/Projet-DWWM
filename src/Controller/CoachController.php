<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $allUsers = $em->getRepository(User::class)->findAll();

        $coachsBDD = array_filter($allUsers, function (User $user) {
            return in_array('ROLE_COACH', $user->getRoles());
        });

        if (!empty($coachsBDD)) {
            $coachs = array_values($coachsBDD);
        } else {
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
        }

        return $this->render('coach/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }

    #[Route('/coachs/{id}', name: 'app_coach_show')]
    public function show(mixed $id, EntityManagerInterface $em): Response
    {
        // 1. ID coach 2
        if ((string)$id === '2') {
            $coach = [
                'id' => 2,
                'nom' => 'JONATHAN SETIAO',
                'specialite' => 'COACH FITNESS & BIEN-ÊTRE',
                'description' => 'Passionné par le fitness, la musculation et le bien-être général, Jonathan accompagne ses clients dans la transformation physique et le rééquilibrage de leur hygiène de vie. Sa méthode repose sur un entraînement adapté, la régularité et le dépassement de soi.',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=800',
                'education' => [
                    'Licence STAPS - Entraînement Sportif et Métiers de la Forme.',
                    'Spécialisation en programmation de remise en forme et suivi nutritionnel.'
                ],
                'certifications' => [
                    'BPJEPS AF (Activités de la Forme)' => 'option Haltérophilie et Musculation.',
                    'Certification Nutrition Sportive' => 'optimisation de la composition corporelle et santé globale.'
                ],
                'note' => '4.8/5',
                'nbAvis' => 98
            ];
        } else {
            // 2. Id coach 1
            $coach = [
                'id' => 1,
                'nom' => 'LOIC LECLAIR',
                'specialite' => 'COACH BOXE ANGLAISE',
                'description' => 'Passionné de boxe depuis plusieurs années, Leclair partage son expérience avec tous les niveaux, du débutant au confirmé. Son objectif est d\'aider chacun à progresser, améliorer sa condition physique et gagner en confiance.',
                'photo' => 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&q=80&w=800',
                'education' => [
                    'Formation en sports de combat et préparation physique de haut niveau.',
                    'Spécialisation en boxe anglaise et techniques de coaching mental.'
                ],
                'certifications' => [
                    'Fédération Française de Boxe' => 'formations fédérales d\'entraîneur et d\'éducateur.',
                    'Fédération Française de Savate Boxe Française' => 'diplômes d\'initiateur, moniteur et entraîneur.'
                ],
                'note' => '4.9/5',
                'nbAvis' => 120
            ];
        }

        return $this->render('coach/show.html.twig', [
            'coach' => $coach,
        ]);
    }
}