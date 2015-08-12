<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\IssueCodeGenerator;

use Anyt\BugTrackerBundle\Entity\Issue;
use Anyt\BugTrackerBundle\IssueCodeGenerator\BasicGenerator;

class BasicGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new BasicGenerator();

        $issue = $this->getMockBuilder('Anyt\BugTrackerBundle\Entity\Issue')
            ->getMock();

        $issue->method('getId')
            ->willReturn(1);

        $this->assertEquals('ISSUE-1', $generator->generate($issue));
    }
}
