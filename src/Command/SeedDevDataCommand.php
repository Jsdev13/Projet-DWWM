<?php

namespace App\Command;

use App\Entity\Coach;
use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed:dev-data',
    description: 'Seed development data for Coach and Salle tables'
)]
class SeedDevDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Seed Coaches
        $coachCount = $this->em->getRepository(Coach::class)->count([]);
        if ($coachCount === 0) {
            $io->section('Création des coachs...');
            
            $coach1 = new Coach();
            $coach1->setName('Loïc Leclair')
                ->setLastname('Leclair')
                ->setFirstname('Loïc')
                ->setImage('default-coach.jpg')
                ->setDescription('Coach sportif passionné avec une expertise en coaching personnalisé.')
                ->setExperience('5 ans d\'expérience en coaching sportif et nutrition.')
                ->setSpeciality('Boxe, Cardio, Musculation');
            
            $coach2 = new Coach();
            $coach2->setName('Jonathan Setiao')
                ->setLastname('Setiao')
                ->setFirstname('Jonathan')
                ->setImage('default-coach.jpg')
                ->setDescription('Expert en préparation physique et remise en forme.')
                ->setExperience('7 ans d\'expérience en coaching et préparation athlétique.')
                ->setSpeciality('Cardio, Musculation, Cross-training');
            
            $this->em->persist($coach1);
            $this->em->persist($coach2);
            $this->em->flush();
            
            $io->success('2 coachs créés : Loïc Leclair, Jonathan Setiao');
        } else {
            $io->info("Table Coach déjà remplie ({$coachCount} coachs existants)");
        }

        // Seed Salles
        $salleCount = $this->em->getRepository(Salle::class)->count([]);
        if ($salleCount === 0) {
            $io->section('Création des salles...');
            
            $salle1 = new Salle();
            $salle1->setName('Salle 1 - Boxe');
            
            $salle2 = new Salle();
            $salle2->setName('Salle 2 - Cardio');
            
            $salle3 = new Salle();
            $salle3->setName('Salle 3 - Musculation');
            
            $this->em->persist($salle1);
            $this->em->persist($salle2);
            $this->em->persist($salle3);
            $this->em->flush();
            
            $io->success('3 salles créées : Salle 1 - Boxe, Salle 2 - Cardio, Salle 3 - Musculation');
        } else {
            $io->info("Table Salle déjà remplie ({$salleCount} salles existantes)");
        }

        $io->success('Données de développement créées avec succès !');
        
        return Command::SUCCESS;
    }
}
