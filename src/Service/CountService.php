<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountService
{
    private EntityManagerInterface $ems;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->ems = $entityManager;
    }

    public function countProduct(int $userId): int
    {
        $userRepository = $this->ems->getRepository(User::class);
        $user = $userRepository->find($userId);
        if (is_null($user)) {
            throw new NotFoundHttpException("User does not exist");
        }
        $paniers = $user->getPaniers();

        dump($paniers);

        return count($paniers);
    }

}