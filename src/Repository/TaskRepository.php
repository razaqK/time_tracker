<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\Tracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param null $keyword
     * @param int $page
     * @param int $limit
     * @param null $name
     * @param null $status
     * @param bool $returnOne
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAll($keyword = null, $page = 0, $limit = 20, $name = null, $status = null, $returnOne = false)
    {
        $query = $this->createQueryBuilder('t')
            ->select(['t.id, t.name, t.description, t.status, t.created_at, t.updated_at, tr.state, tr.total_seconds, tr.start_time'])
            ->leftJoin('t.tracker', 'tr');

        if (!empty($keyword)) {
            $query->andWhere('t.name LIKE :keyword or t.description LIKE :keyword')
                ->setParameter('keyword', "%$keyword%");
        }

        if (!empty($page) && !empty($limit)) {
            $offset = $page <= 0 ? 0 : ($page - 1) * $limit;
            $query->setFirstResult($offset);
            $query->setMaxResults($limit);
        }

        if (!empty($limit) && empty($page)) {
            $query->setMaxResults($limit);
        }

        if (!empty($name)) {
            $query->andWhere('t.name = :name')
                ->setParameter('name', $name);
        }

        if (!empty($status)) {
            $query->andWhere('t.status = :status')
                ->setParameter('status', $status);
        }

        if ($returnOne) {
            $query->setMaxResults(1);
            return $query->getQuery()
                ->getSingleResult();
        }

        return $query->getQuery()
            ->getResult();
    }
}
