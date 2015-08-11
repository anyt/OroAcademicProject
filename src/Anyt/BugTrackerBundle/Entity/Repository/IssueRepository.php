<?php


namespace Anyt\BugTrackerBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;

class IssueRepository extends EntityRepository
{
    /**
     * Returns top $limit issues grouped by status
     *
     * @param  AclHelper $aclHelper
     * @param  int $limit
     * @return array     [itemCount, status]
     */
    public function getIssuesByStatus(AclHelper $aclHelper, $limit = 10)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.status as label', 'count(i.id) as itemCount')
            ->groupBy('i.status')
            ->setMaxResults($limit);

        return $aclHelper->apply($qb)->getArrayResult();
    }
}