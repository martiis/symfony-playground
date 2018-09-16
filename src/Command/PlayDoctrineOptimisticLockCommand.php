<?php

namespace App\Command;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PlayDoctrineOptimisticLockCommand extends Command
{
    protected static $defaultName = 'play:doctrine:optimistic-lock';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Plays optimistic lock')
            ->addArgument('id', InputArgument::OPTIONAL, 'Person id to fetch', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $faker = new Factory();
        $faker = $faker->create();

        $person = $this
            ->entityManager
            ->getRepository(Person::class)
            ->find($input->getArgument('id'));

        $oldName = $person->getName();
        $newName = $faker->name;

        $person->setName($newName);

        $io->ask('<Pause>');

        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $io->comment('Success. Old name - "' . $oldName . '", new name - "' . $newName . '"');
    }
}
