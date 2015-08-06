<?php

namespace Anyt\BugTrackerBundle\IssueCodeGenerator;

use Anyt\BugTrackerBundle\Entity\Issue;

interface GeneratorInterface
{
    public function generate(Issue $issue);
}
