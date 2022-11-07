<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 *
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function add(Feedback $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Feedback $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findFeedbackSince($issue, $received, $date)
	{
		return $this->createQueryBuilder('f')
		            ->where('f.issue = :issue')->setParameter('issue', $issue)
		            ->andWhere('f.received = :received')->setParameter('received', $received)
		            ->andWhere('f.createdAt >= :date')->setParameter('date', $date)
		            ->getQuery()
		            ->getResult()
		;
	}

	public function findIssueFeedbackSince($issue, $date)
	{
		return $this->createQueryBuilder('f')
			->where('f.issue = :issue')->setParameter('issue', $issue)
			->andWhere('f.createdAt >= :date')->setParameter('date', $date)
			->orderBy('f.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}

	public function findReceivedFeedbackSince($received, $date)
	{
		return $this->createQueryBuilder('f')
			->where('f.received = :received')->setParameter('received', $received)
			->andWhere('f.createdAt >= :date')->setParameter('date', $date)
			->orderBy('f.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}

	public function findReceivedFeedbackBefore( $received, $date )
	{
		return $this->createQueryBuilder('f')
			->where('f.received = :received')->setParameter('received', $received)
			->andWhere('f.createdAt <= :date')->setParameter('date', $date)
			->orderBy('f.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}
}
