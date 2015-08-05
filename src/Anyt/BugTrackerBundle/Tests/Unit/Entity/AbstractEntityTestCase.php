<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Entity;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Util\ClassUtils;

abstract class AbstractEntityTestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_ID = 123;

    protected $entity;

    protected function setUp()
    {
        $name = $this->getEntityFQCN();
        $this->entity = new $name();
    }

    public function tearDown()
    {
        unset($this->entity);
    }

    /**
     * @dataProvider  getSetDataProvider
     *
     * @param string $property
     * @param mixed  $value
     * @param mixed  $expected
     */
    public function testSetGet($property, $value = null, $expected = null)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        if ($value !== null) {
            $propertyAccessor->setValue($this->entity, $property, $value);
        }
        $this->assertEquals($expected, $propertyAccessor->getValue($this->entity, $property));
    }

    /**
     * @return array
     */
    abstract public function getSetDataProvider();

    /**
     * @return string
     */
    abstract public function getEntityFQCN();

    public function setEntityId()
    {
        $ref = new \ReflectionProperty(ClassUtils::getClass($this->entity), 'id');
        $ref->setAccessible(true);
        $ref->setValue($this->entity, self::TEST_ID);
    }
}
