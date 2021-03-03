<?php

namespace App\Command;

use App\Entity\User;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendPeriodicEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-periodic-emails';

    private $mailer;

    public function __construct(

        \Swift_Mailer $mailer,
        EntityManagerInterface $entityManager,
        Environment $twig

    )

    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->twig = $twig;

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

        $user = $this->entityManager
                     ->getRepository(User::class)
                     ->findOneById(1);

        //dump($user);

        $userFollows = $user->getUserFollows();

        //last time user accessed notification page
        $lastConnectionDate = $user->getLastNotificationsConnection();
 
        $message = (
            
            new \Swift_Message('Test emails automatisés'))

            ->setFrom(['contact@projetdesprojets.com'=>'Projet des Projets'])

            ->setTo('lauguy@free.fr')

            ->setBody(

                $this->twig->render(

                    'emails/notifications.html.twig',[

                        'userFollows' => $userFollows,
                        'lastConnectionDate' => $lastConnectionDate,
                        'displayContext' => 'email',
                    ]
                ),
                
                'text/html'
            );

            
        $this->mailer->send($message);

        /*
        $headers = 'From: contact@projetdesprojets.com' . "\n";
        $headers .= 'Reply-To: contact@projetdesprojets.com' . "\n";
        $headers .= 'Content-Type: text/html; charset="utf-8"' . "\n";
        $headers .= 'Content-Transfer-Encoding: 8bit';
        $sendMails = mail(
            'lauguy@free.fr',
            'Sujet',
            'Contenu',
            $headers); */




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
