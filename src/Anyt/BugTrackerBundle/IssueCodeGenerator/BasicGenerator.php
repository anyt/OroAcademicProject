<?php

namespace Anyt\BugTrackerBundle\IssueCodeGenerator;


use Anyt\BugTrackerBundle\Entity\Issue;

class BasicGenerator implements GeneratorInterface
{
    public function generate(Issue $issue)
    {
        return sprintf('ISSUE-%d', $issue->getId());

    }
}