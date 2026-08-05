<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:reset-admin')]
class ResetAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('admin@gmail.com');
        }

        $user->setPrenom('Admin');
        $user->setIsVerified(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, 'admin123'));

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('<info>Le compte admin@gmail.com a ete mis a jour avec succes !</info>');

        return Command::SUCCESS;
    }
}