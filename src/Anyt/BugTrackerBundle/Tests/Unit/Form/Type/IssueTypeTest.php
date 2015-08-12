<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Anyt\BugTrackerBundle\Form\Type\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var IssueType */
    protected $type;

    protected function setUp()
    {
        $this->type = new IssueType();
    }

    protected function tearDown()
    {
        unset($this->type);
    }

    public function testInterface()
    {
        $typeName = $this->type->getName();
        $this->assertInternalType('string', $typeName);
        $this->assertNotEmpty($typeName);
    }

    public function testBuildForm()
    {
//        /** @var \PHPUnit_Framework_MockObject_MockObject|FormBuilder $builder */
//        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
//            ->disableOriginalConstructor()
//            ->getMock();

//        $builder->expects($this->atLeastOnce())
//            ->method('add')
//            ->with(
//                $this->isType('string'),
//                $this->isType('string'),
//                $this->callback(
//                    function ($item) {
//                        $this->assertInternalType('array', $item);
//                        $this->assertArrayHasKey('label', $item);
//
//                        return true;
//                    }
//                )
//            )
//            ->
//            will($this->returnSelf());

//        $this->type->buildForm($builder, []);
    }

    public function testSetDefaultOptions()
    {
        /** @var OptionsResolverInterface $resolver */
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');

        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));

        $this->type->setDefaultOptions($resolver);
    }
}