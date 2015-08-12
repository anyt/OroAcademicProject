<?php


namespace Anyt\BugTrackerBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;

class IssueRepository extends EntityRepository
{
    /**
     * Returns top $limit issues grouped by status
     *
     * @param  int $limit
     * @return QueryBuilder
     */
    public function getIssuesByStatus($limit = 10)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.status as label', 'count(i.id) as itemCount')
            ->groupBy('i.status')
            ->setMaxResults($limit);

        return $qb;
    }
}