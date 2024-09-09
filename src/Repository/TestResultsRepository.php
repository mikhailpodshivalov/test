<?php

namespace App\Repository;

use App\Entity\TestResults;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestResults>
 */
class TestResultsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestResults::class);
    }

    public function save(TestResults $testResults): void
    {
        $this->getEntityManager()->persist($testResults);
        $this->getEntityManager()->flush();
    }
}
