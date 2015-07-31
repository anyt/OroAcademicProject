<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\IssueResolution;

class IssueResolutionTest extends AbstractEntityTestCase
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
        $name = IssueResolution::TYPE_DONE;
        $title = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam';
        $issue = $this->getMock('Anyt\BugTrackerBundle\Entity\Issue');

        return [
            'name' => ['name', $name, $name],
            'title' => ['title', $title, $title],
            'issue' => ['issue', $issue, $issue],

        ];
    }
}
