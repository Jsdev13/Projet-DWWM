<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SeanceController extends AbstractController
{
    #[Route('/seances', name: 'app_seance_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $seances = $em->getRepository(Seance::class)->findAll();

        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }

    #[Route('/seance/{id}', name: 'app_seance_show', requirements: ['id' => '\d+'])]
    public function show(?Seance $seance): Response
    {
        if (!$seance) {
            throw $this->createNotFoundException('Cette séance n\'existe pas dans la base de données.');
        }

        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/seance/{id}/reserver', name: 'app_seance_reserver', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserver(Seance $seance, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Empêcher les doublons
        $existingReservation = $em->getRepository(Reservation::class)->findOneBy([
            'member' => $user,
            'seance' => $seance,
        ]);
        if ($existingReservation) {
            $this->addFlash('warning', 'Vous avez déjà réservé cette séance.');
            return $this->redirectToRoute('app_profile');
        }

        // Vérifier que la séance n'est pas complète
        $nbReservations = $em->getRepository(Reservation::class)->count(['seance' => $seance]);
        if ($nbReservations >= $seance->getCapacityMax()) {
            $this->addFlash('warning', 'Désolé, cette séance est complète, il n\'y a plus de place disponible.');
            return $this->redirectToRoute('app_seance_show', ['id' => $seance->getId()]);
        }

        // Création de la réservation en BDD
        $reservation = new Reservation();
        $reservation->setMember($user);
        $reservation->setSeance($seance);
        $reservation->setStatut('CONFIRME');

        // Définition de la date courante
        $now = new \DateTime();
        $reservation->setDateCreate($now);

        // Remplissage dynamique du champ created_at selon ce que prend l'entité
        if (method_exists($reservation, 'setCreatedAt')) {
            try {
                $reservation->setCreatedAt(new \DateTimeImmutable());
            } catch (\TypeError $e) {
                $reservation->setCreatedAt($now);
            }
        }

        $em->persist($reservation);
        $em->flush();

        // Utilisation de getName() au lieu de getTitre()
        $this->addFlash('success', sprintf('Félicitations ! Votre place pour "%s" est réservée.', $seance->getName()));
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/creer-seances-test', name: 'app_test_seances')]
public function creerSeancesTest(EntityManagerInterface $em): Response
{
    // Séance 1
    $s1 = new Seance();
    $s1->setName('Hypertrophie : Force');
    $s1->setDate(new \DateTime('2026-08-19'));
    $s1->setStartTime(new \DateTime('10:00:00'));
    $s1->setEndTime(new \DateTime('10:45:00'));
    $s1->setLevel('Avancé');
    $s1->setCapacityMax(15);

    // Séance 2
    $s2 = new Seance();
    $s2->setName('Boxe : Shadow-boxing');
    $s2->setDate(new \DateTime('2026-09-21'));
    $s2->setStartTime(new \DateTime('14:00:00'));
    $s2->setEndTime(new \DateTime('14:20:00'));
    $s2->setLevel('Intermédiaire');
    $s2->setCapacityMax(10);

    // Séance 3
    $s3 = new Seance();
    $s3->setName('Cardio : Endurance');
    $s3->setDate(new \DateTime('2026-10-18'));
    $s3->setStartTime(new \DateTime('18:00:00'));
    $s3->setEndTime(new \DateTime('18:15:00'));
    $s3->setLevel('Débutant');
    $s3->setCapacityMax(20);

    // Séance 4 - dédiée au test du contrôle de capacité (1 seule place)
    $s4 = new Seance();
    $s4->setName('TEST Capacité - 1 place');
    $s4->setDate(new \DateTime('2026-08-25'));
    $s4->setStartTime(new \DateTime('09:00:00'));
    $s4->setEndTime(new \DateTime('09:30:00'));
    $s4->setLevel('Débutant');
    $s4->setCapacityMax(1);

    // Séance 5 - dédiée au test de l'historique (date PASSÉE)
    $s5 = new Seance();
    $s5->setName('Boxe : Test Historique');
    $s5->setDate(new \DateTime('2025-01-10'));
    $s5->setStartTime(new \DateTime('10:00:00'));
    $s5->setEndTime(new \DateTime('10:45:00'));
    $s5->setLevel('Débutant');
    $s5->setCapacityMax(10);

    // Enregistrement en base de données PostgreSQL
    $em->persist($s1);
    $em->persist($s2);
    $em->persist($s3);
    $em->persist($s4);
    $em->persist($s5);
    $em->flush();

    return new Response('<h1>✅ 5 séances de test ont été créées avec succès en BDD !</h1><p><a href="/">Retourner à l\'accueil</a></p>');
}

    #[Route('/historique', name: 'app_historique')]
    #[IsGranted('ROLE_USER')]
    public function historique(EntityManagerInterface $em): Response
    {
    $user = $this->getUser();

    $historiques = $em->getRepository(Reservation::class)->createQueryBuilder('r')
        ->join('r.seance', 's')
        ->addSelect('s')
        ->where('r.member = :user')
        ->andWhere('s.date < :today')
        ->setParameter('user', $user)
        ->setParameter('today', new \DateTime('today'))
        ->orderBy('s.date', 'DESC')
        ->getQuery()
        ->getResult();

    return $this->render('historique/index.html.twig', [
        'historiques' => $historiques,
        'total_historique' => count($historiques),
    ]);
    }
}