<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\IssuePriority;

class IssuePriorityTest extends AbstractEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getEntityFQCN()
    {
        return 'Anyt\BugTrackerBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritdoc}
     */
    public function getSetDataProvider()
    {
        $name = IssuePriority::TYPE_BLOCKER;
        $title = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam';
        $weight = 5;
        $issues = $this->getMock('Doctrine\Common\Collections\ArrayCollection');

        return [
            'name' => ['name', $name, $name],
            'title' => ['title', $title, $title],
            'weight' => ['weight', $weight, $weight],
            'issues' => ['issues', $issues, $issues],

        ];
    }
}
