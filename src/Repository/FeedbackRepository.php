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
	private string $tableName;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

	/**
	 * @return string
	 */
	public function getTableName(): string
	{
		return $this->tableName;
	}

	/**
	 * @param string $tableName
	 */
	public function setTableName(string $tableName): void
	{
		$this->tableName = htmlspecialchars($tableName) ;
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

	public function findAllFeedBackIssues($issues)
	{
		return $this->createQueryBuilder('f')
			->where('f.issue = :issue')
			->setParameter('issue', $issues)
			->orderBy('f.createdAt','DESC')
			->getQuery()
			->getResult();
	}
	public function findAllFeedBackRecived($reciveds)
	{
		return $this->createQueryBuilder('f')
			->where('f.received = :received')
			->setParameter('received', $reciveds)
			->orderBy('f.createdAt','DESC')
			->getQuery()
			->getResult();
	}


	public function findIssueFeedbackSendBetweenDate($param, $start, $end)
	{
		$qb = $this->createQueryBuilder('f');

		$qb->where('f.'.$this->getTableName().' = :table')
		   ->andWhere($qb->expr()
		                 ->between(
							 'f.createdAt',
							 ':start',
							 ':end'
            ))
			->setParameters([
				'table'=> $param,
				'start' => $start->format('Y/m/d'),
				'end' => $end->format('Y/m/d')
            ])
			->orderBy('f.createdAt', 'DESC');
		return $qb->getQuery()
		          ->getResult();

	}

	public function findIssueFeedbackSinceBefore($issue, $date)
	{
		return $this->createQueryBuilder('f')
			->where('f.issue = :issue')->setParameter('issue', $issue)
			->andWhere('f.createdAt <= :date')->setParameter('date', $date)
			->orderBy('f.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}


	public function findReceivedFeedbackToday($received, $date)
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
