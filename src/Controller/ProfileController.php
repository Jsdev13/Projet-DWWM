<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Récupère les réservations Doctrine associées à cet utilisateur
        $reservations = $reservationRepository->findBy(
            ['member' => $user], 
            ['date_create' => 'DESC']
        );

        return $this->render('profile/index.html.twig', [
            'reservations' => $reservations,
            'total_seances' => count($reservations),
        ]);
    }

    #[Route('/profile/reservation/{id}/annuler', name: 'app_profile_annuler_seance', methods: ['POST'])]
    public function annuler(Reservation $reservation, EntityManagerInterface $em): Response
    {
        // Vérification de sécurité : l'utilisateur ne peut annuler que sa propre réservation
        if ($reservation->getMember() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cette réservation.');
        }

        $em->remove($reservation);
        $em->flush();

        $this->addFlash('success', 'Votre séance a été annulée.');

        return $this->redirectToRoute('app_profile');
    }
}