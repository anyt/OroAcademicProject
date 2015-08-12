<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\ConfigExpression;

use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Component\PropertyAccess\PropertyPath;

use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;

use Anyt\BugTrackerBundle\ConfigExpression\IsInstanceOf;

class IsInstanceOfTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var IsInstanceOf
     */
    protected $condition;

    protected function setUp()
    {
        $this->condition = new IsInstanceOf(new ContextAccessor());
    }

    /**
     * @dataProvider isAllowedDataProvider
     *
     * @param array $options
     * @param $context
     * @param $expectedResult
     */
    public function testIsAllowed(array $options, $context, $expectedResult)
    {
        $this->condition->initialize($options);
        $this->assertEquals($expectedResult, $this->condition->isAllowed($context));
    }

    /**
     * @return array
     */
    public function isAllowedDataProvider()
    {
        $options = ['left' => new PropertyPath('[foo]'), 'right' => new PropertyPath('[bar]')];

        return [
            'right' => [
                'options' => $options,
                'context' => ['foo' => new \stdClass(), 'bar' => '\stdClass'],
                'expectedResult' => true
            ],
            'wrong' => [
                'options' => $options,
                'context' => ['foo' => new \stdClass(), 'bar' => '\stdClass1'],
                'expectedResult' => false
            ],
        ];
    }

    public function testInterface()
    {
        $typeName = $this->condition->getName();
        $this->assertInternalType('string', $typeName);
        $this->assertNotEmpty($typeName);
    }

}
