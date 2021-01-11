<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserFollowService {

    protected $manager;
    protected $session;

    public function __construct(EntityManagerInterface $manager, SessionInterface $session) {

        $this->manager = $manager;

        $this->session = $session;

    }





}
