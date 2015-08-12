<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\Collections\ArrayCollection;

class IssueTest extends AbstractEntityTestCase
{
    const TEST_ID = 123;

    /**
     * @var Issue
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function getEntityFQCN()
    {
        return 'Anyt\BugTrackerBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
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
        $status = Issue::STATUS_CLOSED;
        $owner = $user;
        $organization = $this->getMock('Oro\Bundle\OrganizationBundle\Entity\Organization');
        $assignee = $user;
        $collaborators = new ArrayCollection();
        $relatedIssues = new ArrayCollection();
        $priority = $this->getMock('Anyt\BugTrackerBundle\Entity\IssuePriority');
        $resolution = $this->getMock('Anyt\BugTrackerBundle\Entity\IssueResolution');
        $tags = new ArrayCollection();
        $parent = new Issue;
        $parent->setType(Issue::TYPE_STORY);
        $children = new ArrayCollection();

        return [
            'summary' => ['summary', $summary, $summary],
            'code' => ['code', $code, $code],
            'description' => ['description', $description, $description],
            'created' => ['created', $created, $created],
            'updated' => ['updated', $updated, $updated],
            'type' => ['type', $type, $type],
            'status' => ['status', $status, $status],
            'owner' => ['owner', $owner, $owner],
            'organization' => ['organization', $organization, $organization],
            'assignee' => ['assignee', $assignee, $assignee],
            'collaborators' => ['collaborators', $collaborators, $collaborators],
            'relatedIssues' => ['relatedIssues', $relatedIssues, $relatedIssues],
            'priority' => ['priority', $priority, $priority],
            'resolution' => ['resolution', $resolution, $resolution],
            'tags' => ['tags', $tags, $tags],
            'parent' => ['parent', $parent, $parent],
            'children' => ['children', $children, $children],
        ];
    }

    public function testTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', $this->entity);

        $this->assertNull($this->entity->getTaggableId());

        $this->setEntityId();

        $this->assertSame(self::TEST_ID, $this->entity->getTaggableId());
    }

    public function testAddCollaborator()
    {
        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $entity = new Issue();


        $this->assertEmpty($entity->getCollaborators()->toArray());

        $entity->addCollaborator($user);
        $actualCollaborators = $entity->getCollaborators()->toArray();
        $this->assertCount(1, $actualCollaborators);
        $this->assertEquals($user, current($actualCollaborators));

    }

    public function testRemoveCollaborator()
    {
        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $entity = new Issue();

        $entity->addCollaborator($user);
        $this->assertCount(1, $entity->getCollaborators()->toArray());

        $entity->removeCollaborator($user);
        $this->assertEmpty($entity->getCollaborators()->toArray());
    }

}
