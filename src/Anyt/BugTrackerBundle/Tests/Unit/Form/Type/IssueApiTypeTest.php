<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Form\Type;

use Anyt\BugTrackerBundle\Form\Type\IssueApiType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueApiTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueApiType
     */
    private $type;

    /**
     * init environment
     */
    public function setUP()
    {
        $nameFormatter = $this->getMockBuilder('Oro\Bundle\LocaleBundle\Formatter\NameFormatter')
            ->disableOriginalConstructor()
            ->getMock();

        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();

        $securityFacade = $this->getMockBuilder('Oro\Bundle\SecurityBundle\SecurityFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $this->type = new IssueApiType($router, $nameFormatter, $securityFacade);
    }

    public function testSetDefaultOptions()
    {
        /** @var OptionsResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');

        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));

        $this->type->setDefaultOptions($resolver);
    }

    public function testName()
    {
        $this->assertEquals('issue', $this->type->getName());
    }
}
