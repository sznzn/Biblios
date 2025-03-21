<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:deactive-user',
    description: 'Désactive les utilisateurs inactifs'
)]
class DeactiveUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Nombre de jours', 30)
            ->addOption('dry-run', 'dr', InputOption::VALUE_NONE, 'Affiche les utilisateurs qui seront désactivés sans les désactiver')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $days = $input->getOption('days');
        $dryRun = $input->getOption('dry-run');
        $date = new \DateTimeImmutable("-{$days} days");

        $users = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.lastLoginAt < :date OR u.lastLoginAt IS NULL')
            ->andWhere('u.roles NOT LIKE :adminRole')
            ->setParameter('date', $date)
            ->setParameter('adminRole', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getResult();

        if (empty($users)) {
            $io->success('Aucun utilisateur n\'a été désactivé');
            return Command::SUCCESS;
        }

        $io->section(sprintf('Désactivation de %d utilisateurs', count($users)));

        foreach ($users as $user) {
            $io->writeln(sprintf(
                'Désactivation de %s (dernière connexion: %s)', 
                $user->getEmail(),
                $user->getLastLoginAt() ? $user->getLastLoginAt()->format('Y-m-d H:i:s') : 'jamais'
            ));
            if (!$dryRun) {
                $user->setIsActive(false);
            }
        }

        if (!$dryRun) {
            $this->entityManager->flush();
            $io->success('Les utilisateurs ont été désactivés');
        } else {
            $io->note('Simulation terminée - aucune modification n\'a été effectuée');
        }

        return Command::SUCCESS;
    }
}
