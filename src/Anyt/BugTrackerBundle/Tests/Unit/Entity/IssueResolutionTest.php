<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Anyt\BugTrackerBundle\Entity\IssueResolution;

class IssueResolutionTest extends AbstractEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getEntityFQCN()
    {
        return 'Anyt\BugTrackerBundle\Entity\IssueResolution';
    }

    /**
     * {@inheritdoc}
     */
    public function getSetDataProvider()
    {
        $name = IssueResolution::TYPE_DONE;
        $title = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam';
        $issues = $this->getMock('Doctrine\Common\Collections\ArrayCollection');

        return [
            'name' => ['name', $name, $name],
            'title' => ['title', $title, $title],
            'issues' => ['issues', $issues, $issues],

        ];
    }
}
