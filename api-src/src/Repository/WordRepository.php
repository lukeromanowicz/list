<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Word;
use App\Service\Words\WordDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    public function findByUserIp($userIp)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
        'SELECT w, u
        FROM App\Entity\Word w
        INNER JOIN w.user u
        WHERE u.ip = :ip'
        )->setParameter('ip', $userIp);

        return $query->getResult();
    }

    /**
     * @param WordDTO[] $words
     * @param User $user
     */
    public function updateWithDTOs(array $words, User $user)
    {
        $dbConnection = $this->getEntityManager()
            ->getConnection();
        // TODO: refactor into batch or/and asynchronous queries if there is a need to support large volume of text at once

        $query = <<<SQL
            INSERT INTO words (`word`, `count`, `user_id`) VALUES
         SQL;

        $i = 0;
        foreach ($words as $word) {
            $query .= '(:word' . $i++ . ',' . $word->count . ',' . $user->getId() .'),';
        }
        $query = rtrim($query, ",");

        $query .= <<<SQL
            ON DUPLICATE KEY UPDATE    
            `count`= `count` + VALUES(`count`);
SQL;

        $stmt = $dbConnection->prepare($query);

        $i = 0;
        foreach ($words as $word) {
            $stmt->bindValue('word' . $i++, $word->word);
        }

        return $stmt->execute();
    }
}
