<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\SeanceRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/dashboard/admin/membres-inscrits', name: 'admin_members_registered', methods: ['GET'])]
    public function registeredMembers(
        SeanceRepository $seanceRepository,
        ReservationRepository $reservationRepository
    ): Response {
        return $this->render('admin/members/registered.html.twig', [
            'reservations' => $reservationRepository->findAllWithMemberAndSeance(),
            'seances' => $seanceRepository->findBy([], ['date' => 'DESC', 'start_time' => 'ASC']),
        ]);
    }

    #[Route('/dashboard/admin/utilisateurs', name: 'admin_users_index', methods: ['GET'])]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/dashboard/admin/inscription/{id}/desinscrire', name: 'admin_member_unregister', requirements: ['id' => '\\d+'], methods: ['POST'])]
    public function unregisterMember(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid('unregister-' . $reservation->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'La désinscription a échoué : jeton de sécurité invalide.');

            return $this->redirectToRoute('admin_members_registered');
        }

        $memberName = $reservation->getMember()?->getPrenom()
            ?: $reservation->getMember()?->getEmail()
            ?: 'Le membre';
        $courseName = $reservation->getSeance()?->getName() ?: 'ce cours';

        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', sprintf('%s a bien été désinscrit(e) du cours « %s ».', $memberName, $courseName));

        return $this->redirectToRoute('admin_members_registered');
    }
}
