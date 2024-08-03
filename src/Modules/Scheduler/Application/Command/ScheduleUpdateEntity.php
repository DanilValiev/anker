<?php

namespace App\Modules\Scheduler\Application\Command;

use App\Modules\Scheduler\Infrastructure\Helper\EntityChangeApply;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'scheduler:update:entity')]
class ScheduleUpdateEntity extends Command
{
    protected static $defaultName = 'scheduler:update:entity';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityChangeApply $changeApply
    )
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->addOption('changes', null, InputOption::VALUE_REQUIRED)
            ->addOption('jms-job-id', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serializedChanges = $input->getOption('changes');
        $changes = json_decode($serializedChanges, true);

        $this->changeApply->apply($changes);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}