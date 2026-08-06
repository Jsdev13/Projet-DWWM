<?php

namespace App\Tests\Support;

use App\Entity\Categorie;
use App\Entity\Coach;
use App\Entity\Salle;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fournit les données de référence nécessaires aux tests fonctionnels
 * en passant uniquement par Doctrine ORM (aucune commande SQL externe).
 */
trait ReferenceDataTrait
{
    private function ensureAdmin(EntityManagerInterface $em, UserPasswordHasherInterface $hasher): User
    {
        $admin = $em->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']);

        if (!$admin) {
            $admin = new User();
            $admin->setEmail('admin@gmail.com');
            $admin->setPrenom('Admin');
            $admin->setIsVerified(true);
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($hasher->hashPassword($admin, 'admin123'));

            $em->persist($admin);
            $em->flush();
        }

        return $admin;
    }

    private function ensureCategorie(EntityManagerInterface $em): Categorie
    {
        $categorie = $em->getRepository(Categorie::class)->findOneBy([]);

        if (!$categorie) {
            $categorie = new Categorie();
            $categorie->setName('Boxe');
            $categorie->setDescription('Cours de boxe pour tous les niveaux.');

            $em->persist($categorie);
            $em->flush();
        }

        return $categorie;
    }

    private function ensureCoach(EntityManagerInterface $em): Coach
    {
        $coach = $em->getRepository(Coach::class)->findOneBy([]);

        if (!$coach) {
            $coach = new Coach();
            $coach->setName('Loïc Leclair')
                ->setLastname('Leclair')
                ->setFirstname('Loïc')
                ->setImage('default-coach.jpg')
                ->setDescription('Coach sportif passionné.')
                ->setExperience('5 ans d\'expérience.')
                ->setSpeciality('Boxe, Cardio');

            $em->persist($coach);
            $em->flush();
        }

        return $coach;
    }

    private function ensureSalle(EntityManagerInterface $em): Salle
    {
        $salle = $em->getRepository(Salle::class)->findOneBy([]);

        if (!$salle) {
            $salle = new Salle();
            $salle->setName('Salle 1 - Boxe');

            $em->persist($salle);
            $em->flush();
        }

        return $salle;
    }
}
