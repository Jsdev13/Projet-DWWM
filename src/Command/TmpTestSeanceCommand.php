<?php

namespace App\Command;

use App\Entity\Seance;
use App\Repository\CategorieRepository;
use App\Repository\CoachRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande temporaire : vérifie que l'enregistrement d'une Seance
 * avec ses relations (categorie, coach, salle) fonctionne en base.
 */
#[AsCommand(name: 'app:tmp:test-seance', description: 'Test temporaire de persistance d\'une Seance')]
class TmpTestSeanceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategorieRepository $categories,
        private CoachRepository $coachs,
        private SalleRepository $salles,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categorie = $this->categories->findOneBy([]);
        $coach = $this->coachs->findOneBy([]);
        $salle = $this->salles->findOneBy([]);

        $output->writeln(sprintf(
            'Catégories: %d | Coachs: %d | Salles: %d',
            count($this->categories->findAll()),
            count($this->coachs->findAll()),
            count($this->salles->findAll()),
        ));

        if (!$categorie || !$coach || !$salle) {
            $output->writeln('<error>Données de référence manquantes (categorie/coach/salle).</error>');

            return Command::FAILURE;
        }

        $seance = new Seance();
        $seance->setName('TMP Test Cours')
            ->setCategorie($categorie)
            ->setCoach($coach)
            ->setSalle($salle)
            ->setLevel('Débutant')
            ->setDate(new \DateTime('+3 days'))
            ->setStartTime(new \DateTime('10:00'))
            ->setEndTime(new \DateTime('11:00'))
            ->setCapacityMax(12);

        $this->em->persist($seance);
        $this->em->flush();

        $id = $seance->getId();
        $output->writeln('<info>Seance enregistrée, id = ' . $id . '</info>');

        // Relecture pour valider les relations persistées
        $this->em->clear();
        /** @var Seance|null $reloaded */
        $reloaded = $this->em->getRepository(Seance::class)->find($id);
        $output->writeln(sprintf(
            'Relu: %s | cat=%s | coach=%s | salle=%s',
            $reloaded->getName(),
            $reloaded->getCategorie()?->getName(),
            $reloaded->getCoach()?->getName(),
            $reloaded->getSalle()?->getName(),
        ));

        // Nettoyage
        $this->em->remove($reloaded);
        $this->em->flush();
        $output->writeln('<info>Seance de test supprimée.</info>');

        return Command::SUCCESS;
    }
}
