<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function saving(User $newUser, ?bool $isSaved)
    {
        $this->getEntityManager()->persist($newUser);

        if($isSaved){
            $this->getEntityManager()->flush();
        }
        return $newUser;
    }

}
