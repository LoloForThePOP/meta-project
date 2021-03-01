<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendPeriodicEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-periodic-emails';

    private $mailer;

    public function __construct(
        \Swift_Mailer $mailer
    )

    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Allow to send periodic emails to users')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $message = (
            
            new \Swift_Message('Test emails automatisés'))

            ->setFrom(['contact@projetdesprojets.com'=>'Projet des Projets'])
            ->setTo('lauguy@free.fr')
            ->setBody(
                'test envoi emails'
            );
            
        $this->mailer->send($message);



        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        
        $option1 = $input->getOption('option1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($option1) {
            $io->note(sprintf('You passed an option: %s', $option1));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');




        return 0;
    }
}
