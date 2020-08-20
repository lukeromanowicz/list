<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Words\UserDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function deleteByIp(string $ip): void
    {
        $user = $this->findOneBy(['ip' => $ip]);

        if (!$user) {
            throw new EntityNotFoundException("User with ip: '${ip}' not found.");
        }
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    public function createFromDTO(UserDTO $userDTO): void
    {
        $em = $this->getEntityManager();
        try {
            $entity = (new User())
                ->setIp($userDTO->ip);
            $em->persist($entity);
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            // user already exists
        }
    }
}
