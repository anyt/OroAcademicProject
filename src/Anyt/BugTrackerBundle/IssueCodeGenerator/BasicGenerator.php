<?php

namespace Anyt\BugTrackerBundle\IssueCodeGenerator;

use Anyt\BugTrackerBundle\Entity\Issue;

class BasicGenerator implements GeneratorInterface
{
    /**
     * @param Issue $issue
     * @return string
     */
    public function generate(Issue $issue)
    {
        return sprintf('ISSUE-%d', $issue->getId());
    }
}
