<?php

namespace Anyt\BugTrackerBundle\IssueCodeGenerator;

use Anyt\BugTrackerBundle\Entity\Issue;

interface GeneratorInterface
{
    /**
     * @param Issue $issue
     * @return mixed
     */
    public function generate(Issue $issue);
}
