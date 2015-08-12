<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\Entity\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

use Oro\Bundle\TestFrameworkBundle\Test\Doctrine\ORM\OrmTestCase;
use Oro\Bundle\TestFrameworkBundle\Test\Doctrine\ORM\Mocks\EntityManagerMock;
use Anyt\BugTrackerBundle\Entity\Repository\IssueRepository;

class IssueRepositoryTest extends OrmTestCase
{
    /** @var EntityManagerMock */
    protected $em;

    protected function setUp()
    {
        $metadataDriver = new AnnotationDriver(
            new AnnotationReader(),
            'OroCRM\Bundle\TaskBundle\Tests\Unit\Fixtures\Entity'
        );

        $this->em = $this->getTestEntityManager();
        $this->em->getConfiguration()->setMetadataDriverImpl($metadataDriver);
        $this->em->getConfiguration()->setEntityNamespaces(
            [
                'AnytBugTrackerBundle' => 'Anyt\BugTrackerBundle\Entity'
            ]
        );
    }

    public function testGetIssuesByStatus()
    {
        $maxResults = 10;
        /** @var IssueRepository $repo */
        $repo = $this->em->getRepository('AnytBugTrackerBundle:Issue');
        $qb = $repo->getIssuesByStatus($maxResults);

        $this->assertEquals(
            'SELECT i.status as label, count(i.id) as itemCount '.
            'FROM Anyt\BugTrackerBundle\Entity\Issue i GROUP BY i.status',
            $qb->getDQL()
        );
        $this->assertEquals($maxResults, $qb->getMaxResults());
    }
}