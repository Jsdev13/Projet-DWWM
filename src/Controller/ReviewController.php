<?php

namespace App\Controller;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
   #[Route('/notes', name: 'app_coach_rate')]
#[Route('/coach/noter', name: 'app_coach_rate_alt')]
public function rate(EntityManagerInterface $em, Request $request): Response
{
    $user = $this->getUser();

    if (!$user) {
        $this->addFlash('danger', 'Vous devez être connecté pour noter un coach.');
        return $this->redirectToRoute('app_login');
    }

    $coachsAutorises = [];

    // On parcourt uniquement les réservations réelles de l'utilisateur
    foreach ($user->getReservations() as $reservation) {
        $seance = $reservation->getSeance();
        if ($seance) {
            $coach = $seance->getCoach();
            $coachName = null;

            if (is_object($coach)) {
                $coachName = method_exists($coach, 'getNom') ? $coach->getNom() : ($coach->getName() ?? null);
            } elseif (is_string($coach) && !empty(trim($coach))) {
                $coachName = trim($coach);
            }

            // Si le champ coach de la séance réservée est vide, on lui attribue Loïc par défaut
            if (!$coachName) {
                $coachName = 'JONATHAN SETIAO';
            }

            // On ajoute le coach de la séance à la liste autorisée
            $coachsAutorises[$coachName] = $coachName;
        }
    }

    if ($request->isMethod('POST')) {
        $coachName = trim((string)$request->request->get('coach_name'));
        $valeur = (int) $request->request->get('rating', 5);

        // Bloque le POST si le coach ne fait pas partie des cours réservés
        if (!in_array($coachName, $coachsAutorises, true)) {
            $this->addFlash('danger', 'Vous ne pouvez noter que les coachs avec lesquels vous avez déjà effectué une séance.');
            return $this->redirectToRoute('app_coach_rate');
        }

        $note = new Note();
        $note->setValeur($valeur);
        $note->setDateCreate(new \DateTime());
        $note->setCoachName($coachName);
        $note->setMember($user);

        $em->persist($note);
        $em->flush();

        return $this->redirectToRoute('app_coach_rate_success', [
            'rating' => $valeur
        ]);
    }

    return $this->render('review/rate.html.twig', [
        'coachs' => $coachsAutorises,
    ]);
}
    #[Route('/coach/noter/succes', name: 'app_coach_rate_success')]
    public function success(Request $request): Response
    {
        $rating = $request->query->get('rating', '5.0');

        return $this->render('review/success.html.twig', [
            'rating' => number_format((float)$rating, 1, '.', ''),
        ]);
    }
}