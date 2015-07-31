<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\IssuePriority;

class IssuePriorityTest extends AbstractEntityTestCase
{
    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Anyt\BugTrackerBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {
        $name = IssuePriority::TYPE_BLOCKER;
        $title = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam';
        $weight = 5;
        $issue = $this->getMock('Anyt\BugTrackerBundle\Entity\Issue');

        return [
            'name' => ['name', $name, $name],
            'title' => ['title', $title, $title],
            'weight' => ['weight', $weight, $weight],
            'issue' => ['issue', $issue, $issue],

        ];
    }
}
