<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    // route /dashboard/admin
    #[Route('/dashboard/admin', name: 'admin_dashboard')]
    public function index(
        SeanceRepository $seanceRepository,
        ReservationRepository $reservationRepository
    ): Response {
        // 1. Récupération de toutes les séances
        $allSeances = $seanceRepository->findBy([], ['id' => 'DESC']);
        $totalCours = count($allSeances);

        // 2. Récupération de toutes les réservations
        $allReservations = $reservationRepository->findAll();
        $inscriptionsSemaine = count($allReservations);

        // 3. Calcul du taux de remplissage global
        $totalCapacity = 0;
        $totalReservations = 0;

        foreach ($allSeances as $seance) {
            $totalCapacity += $seance->getCapacityMax();
            $totalReservations += count($seance->getReservations());
        }

        $tauxRemplissage = $totalCapacity > 0 
            ? round(($totalReservations / $totalCapacity) * 100) 
            : 0;

        // 4. Les 5 derniers cours
        $nextCours = array_slice($allSeances, 0, 5);

        return $this->render('admin/dashboard.html.twig', [
            'totalCours' => $totalCours,
            'coursCetteSemaine' => 0,
            'tauxRemplissage' => $tauxRemplissage,
            'inscriptionsSemaine' => $inscriptionsSemaine,
            'nextCours' => $nextCours,
        ]);
    }
}