<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\Collections\ArrayCollection;

class IssueTest extends AbstractEntityTestCase
{
    const TEST_ID = 123;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Anyt\BugTrackerBundle\Entity\Issue';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');

//        $this->setEntityId();

        $summary = 'Lorem ipsum';
        $code = 'ISSUE-123';
        $description = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
        nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat';
        $created = $now;
        $updated = $now;
        $type = Issue::TYPE_BUG;
        $reporter = $user;
        $assignee = $user;
        $collaborators = new ArrayCollection();
        $relatedIssues = new ArrayCollection();
        $priority = $this->getMock('Anyt\BugTrackerBundle\Entity\IssuePriority');
        $resolution = $this->getMock('Anyt\BugTrackerBundle\Entity\IssueResolution');
        $tags = new ArrayCollection();

        return [
            'summary' => ['summary', $summary, $summary],
            'code' => ['code', $code, $code],
            'description' => ['description', $description, $description],
            'created' => ['created', $created, $created],
            'updated' => ['updated', $updated, $updated],
            'type' => ['type', $type, $type],
            'reporter' => ['reporter', $reporter, $reporter],
            'assignee' => ['assignee', $assignee, $assignee],
            'collaborators' => ['collaborators', $collaborators, $collaborators],
            'relatedIssues' => ['relatedIssues', $relatedIssues, $relatedIssues],
            'priority' => ['priority', $priority, $priority],
            'resolution' => ['resolution', $resolution, $resolution],
            'tags' => ['tags', $tags, $tags],
        ];
    }

    public function testTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', $this->entity);

        $this->assertNull($this->entity->getTaggableId());

        $this->setEntityId();

        $this->assertSame(self::TEST_ID, $this->entity->getTaggableId());
    }

    public function testDoPostPersist()
    {
        $entity = $this->entity;
        $this->setEntityId();
        $this->assertEquals(self::TEST_ID, $entity->getId());
        $entity->doPostPersist();
        $this->assertEquals('ISSUE-123', $entity->getCode());
    }
}
