<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\EventListener;


use Anyt\BugTrackerBundle\Entity\Issue;
use Anyt\BugTrackerBundle\EventListener\IssueCodeListener;
use Anyt\BugTrackerBundle\IssueCodeGenerator\BasicGenerator;

class IssueCodeListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasicGenerator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generator;

    /**
     * @var IssueCodeListener
     */
    protected $listener;

    protected $targetEntity;

    protected $args;

    public function setUp()
    {
        $this->generator = $this->getMock('Anyt\BugTrackerBundle\IssueCodeGenerator\BasicGenerator');

        $this->listener = new IssueCodeListener($this->generator);

        $this->targetEntity = $this->getMock('Anyt\BugTrackerBundle\Entity\Issue');

        $this->args = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();;
    }

    public function testPostPersist()
    {
        $this->args->method('getEntity')->willReturn($this->targetEntity);
        $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->args->method('getEntityManager')->willReturn($em);

        $this->generator->expects($this->once())
            ->method('generate')
            ->with($this->targetEntity);

        $this->targetEntity->expects($this->once())
            ->method('setCode');

        $em->expects($this->once())
            ->method('flush')
            ->with($this->targetEntity);

        $this->listener->postPersist($this->args);
    }
}