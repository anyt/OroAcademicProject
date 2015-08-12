<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\Entity\Repository;

use Anyt\BugTrackerBundle\EventListener\NoteListener;
use Doctrine\Common\Util\ClassUtils;
use Oro\Bundle\NoteBundle\Entity\Note;

class NoteListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NoteListener
     */
    protected $listener;

    /**
     * @var Note
     */
    protected $entity;


    public function setUp()
    {
        $this->listener = new NoteListener();
        $this->entity = $this->getMock('Oro\Bundle\NoteBundle\Entity\Note');
        $target = $this->getMock('Anyt\BugTrackerBundle\Entity\Issue');
        $this->entity->setTarget($target);

    }


    public function testUpdateIssue()
    {
        $ref = new \ReflectionMethod(ClassUtils::getClass($this->listener), 'updateIssue');
        $ref->setAccessible(true);
        $ref->invoke($this->listener, $this->entity);
    }
}
