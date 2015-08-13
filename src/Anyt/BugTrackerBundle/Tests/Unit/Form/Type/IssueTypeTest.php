<?php

namespace Anyt\BugTrackerBundle\Tests\Unit\Form\Type;

use Anyt\BugTrackerBundle\Entity\Issue;
use Anyt\BugTrackerBundle\Form\Type\IssueType;
use Anyt\BugTrackerBundle\Tests\Unit\Form\Type\Stub\EntityType;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\PreloadedExtension;

use Symfony\Component\Form\Test\FormIntegrationTestCase;

class IssueTypeTest extends FormIntegrationTestCase
{
    const USER_CLASS = 'Oro\Bundle\UserBundle\Entity\User';
    const ISSUE_CLASS = 'Anyt\BugTrackerBundle\Entity\Issue';
    const ISSUE_PRIORITY_CLASS = 'Anyt\BugTrackerBundle\Entity\IssuePriority';
    const ISSUE_RESOLUTION_CLASS = 'Anyt\BugTrackerBundle\Entity\IssueResolution';
    /**
     * @var IssueType
     */
    protected $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->markTestIncomplete();
        $this->formType = new IssueType();
        $this->formType->setDataClass(self::ISSUE_CLASS);
        parent::setUp();
    }

    /**
     * @param bool $isValid
     * @param mixed $defaultData
     * @param array $submittedData
     * @param mixed $expectedData
     * @dataProvider submitProvider
     */
    public function testSubmit($isValid, $defaultData, $submittedData, $expectedData)
    {
        $form = $this->factory->create($this->formType, $defaultData, []);

        $this->assertEquals($defaultData, $form->getData());
        $form->submit($submittedData);
        $this->assertEquals($isValid, $form->isValid());
        $this->assertEquals($expectedData, $form->getData());
    }

    /**
     * @return array
     */
    public function submitProvider()
    {
        /** @var User $user */
        $user = $this->getEntity(self::USER_CLASS, 5);


        return [
            'minimal' => [
                'isValid' => true,
                'defaultData' => new Issue(),
                'submittedData' => [
                    'summary' => 'test',
                    'description' => 'test',
                    'type' => Issue::TYPE_STORY,
                    'status' => Issue::STATUS_OPEN,
                    'assignee' => $user->getId(),
                ],
                'expectedData' => (new Issue())
                    ->setSummary('test')
                    ->setDescription('test')
                    ->setType(Issue::TYPE_STORY)
                    ->setStatus(Issue::STATUS_OPEN)
                    ->setAssignee($user)
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $entityType = new EntityType(
            [
                1 => $this->getEntity(self::ISSUE_PRIORITY_CLASS, 1),
                2 => $this->getEntity(self::ISSUE_PRIORITY_CLASS, 2),
                3 => $this->getEntity(self::ISSUE_RESOLUTION_CLASS, 3),
                4 => $this->getEntity(self::ISSUE_RESOLUTION_CLASS, 4),
            ]
        );

        $userType = new EntityType(
            [
                5 => $this->getEntity(self::USER_CLASS, 5),
                6 => $this->getEntity(self::USER_CLASS, 6),
            ],
            'oro_user_select'
        );

//        $tagSelect = $this->getMockBuilder('\Oro\Bundle\TagBundle\Form\Type\TagSelectType')
//            ->disableOriginalConstructor()
//            ->getMock();

        return [
            new PreloadedExtension(
                [
                    'entity' => $entityType,
                    'oro_user_select' => $userType,
//                    'oro_tag_select' => $tagSelect,
                ],
                []
            )
        ];
    }

    /**
     * Test getName
     */
    public function testGetName()
    {
        $this->assertEquals(IssueType::NAME, $this->formType->getName());
    }

    /**
     * @param string $className
     * @param int $id
     *
     * @return object
     */
    protected function getEntity($className, $id)
    {
        $entity = new $className;

        $reflectionClass = new \ReflectionClass($className);
        $method = $reflectionClass->getProperty('id');
        $method->setAccessible(true);
        $method->setValue($entity, $id);

        return $entity;
    }

}