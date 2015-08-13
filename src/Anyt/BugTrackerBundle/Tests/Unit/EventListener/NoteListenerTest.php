<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\EventListener;

use Anyt\BugTrackerBundle\Entity\Issue;
use Anyt\BugTrackerBundle\EventListener\NoteListener;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;

class NoteListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NoteListener
     */
    protected $listener;

    /**
     * @var OnFlushEventArgs|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $args;

    /**
     * @var \Doctrine\ORM\UnitOfWork|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $uow;

    /**
     * @var \Doctrine\ORM\EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $em;

    /**
     * @var Note
     */
    protected $entity;

    /**
     * @var Issue
     */
    protected $targetEntity;

    public function setUp()
    {

        $this->listener = new NoteListener();

        $this->entity = $this->getMock('Oro\Bundle\NoteBundle\Entity\Note');

        $this->targetEntity = new Issue();
        $this->entity->method('getTarget')->willReturn($this->targetEntity);

        $this->args = $this->getMockBuilder('\Doctrine\ORM\Event\OnFlushEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->uow = $this->getMockBuilder('\Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()
            ->getMock();


        $this->em->method('getUnitOfWork')->willReturn($this->uow);

        $metadataFactory = $this->getMock('\Doctrine\ORM\Mapping\ClassMetadataFactory');
        $classMetadata = $this->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();
        $metadataFactory->method('getMetadataFor')->willReturn($classMetadata);

        $this->em->method('getMetadataFactory')->willReturn($metadataFactory);

        $this->args->method('getEntityManager')->willReturn($this->em);

    }

    public function testOnFlush()
    {


        $this->uow->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->willReturn([$this->entity]);
        $this->uow->expects($this->once())
            ->method('getScheduledEntityUpdates')
            ->willReturn([$this->entity]);
        $this->uow->expects($this->once())
            ->method('getScheduledEntityDeletions')
            ->willReturn([$this->entity]);


        /** @var Issue $issue */
        $issue = $this->entity->getTarget();
        $updated = $issue->getUpdated();


        $this->listener->onFlush($this->args);

        $this->assertNotEquals($updated, $issue->getUpdated());

    }
}
