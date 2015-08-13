<?php

namespace Anyt\BugTrackerBundle\Tests\Functional\API;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class SoapIssueTest extends WebTestCase
{
    /**
     * @var array
     */
    protected $issueCreateData = [
        'summary' => 'Issue_name',
        'description' => 'test',
        'type' => 'story',
        'owner' => '1',
        'assignee' => '1',
        'priority' => '1',
        'resolution' => '1'
    ];

    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
        $this->initSoapClient();
    }


    /**
     * @return integer
     */
    public function testCreate()
    {
        $result = $this->soapClient->createIssue($this->issueCreateData);

        $this->assertGreaterThan(0, $result, $this->soapClient->__getLastResponse());

        return $result;
    }

//    /**
//     * @depends testCreate
//     * @param integer $id
//     */
//    public function testCget($id)
//    {
//        $result = $this->soapClient->getIssues();
//        $result = $this->valueToArray($result);
//        $issues = $result['item'];
//
//        $this->assertCount(4, $issues);
//
//        $this->assertArrayIntersectEquals(
//            array(
//                'id' => $id,
//                'subject' => $this->issueCreateData['subject'],
//                'description' => $this->issueCreateData['description'],
//                'owner' => self::$adminUserId,
//                'relatedContact' => null,
//                'relatedAccount' => null,
//                'source' => IssueSource::SOURCE_OTHER,
//                'status' => IssueStatus::STATUS_OPEN,
//                'priority' => IssuePriority::PRIORITY_NORMAL,
//                'updatedAt' => null,
//                'closedAt' => null,
//            ),
//            $issues[0]
//        );
//
//        $this->assertNotEmpty($issues[0]['createdAt']);
//        $this->assertNotEmpty($issues[0]['reportedAt']);
//    }
//
//    /**
//     * @depends testCreate
//     * @param integer $id
//     * @return array
//     */
//    public function testGet($id)
//    {
//        $result = $this->soapClient->getIssue($id);
//        $issue = $this->valueToArray($result);
//
//        $this->assertArrayIntersectEquals(
//            array(
//                'id' => $id,
//                'subject' => $this->issueCreateData['subject'],
//                'description' => $this->issueCreateData['description'],
//                'owner' => self::$adminUserId,
//                'relatedContact' => null,
//                'relatedAccount' => null,
//                'source' => IssueSource::SOURCE_OTHER,
//                'status' => IssueStatus::STATUS_OPEN,
//                'priority' => IssuePriority::PRIORITY_NORMAL,
//                'updatedAt' => null,
//                'closedAt' => null,
//            ),
//            $issue
//        );
//
//        $this->assertNotEmpty($issue['createdAt']);
//        $this->assertNotEmpty($issue['reportedAt']);
//
//        return $issue;
//    }
//
//    /**
//     * @depends testGet
//     * @param array $originalIssue
//     * @return integer
//     */
//    public function testUpdate(array $originalIssue)
//    {
//        $id = $originalIssue['id'];
//
//        $updateData = [
//            'subject' => 'Updated subject',
//            'description' => 'Updated description',
//            'resolution' => 'Updated resolution',
//            'status' => IssueStatus::STATUS_CLOSED,
//            'priority' => IssuePriority::PRIORITY_LOW,
//            'source' => IssueSource::SOURCE_WEB,
//            'relatedContact' => self::$contactId,
//            'assignedTo' => self::$adminUserId,
//        ];
//
//        $result = $this->soapClient->updateIssue($id, $updateData);
//        $this->assertTrue($result, $this->soapClient->__getLastResponse());
//
//        $updatedIssue = $this->soapClient->getIssue($id);
//        $updatedIssue = $this->valueToArray($updatedIssue);
//
//        $this->assertNotEmpty($updatedIssue['updatedAt']);
//        $this->assertNotEmpty($updatedIssue['closedAt']);
//
//        $expectedIssue = array_merge($originalIssue, $updateData);
//        $expectedIssue['updatedAt'] = $updatedIssue['updatedAt'];
//        $expectedIssue['closedAt'] = $updatedIssue['closedAt'];
//
//        return $id;
//    }
//
//    /**
//     * @param integer $id
//     * @depends testCreate
//     */
//    public function testDelete($id)
//    {
//        $result = $this->soapClient->deleteIssue($id);
//        $this->assertTrue($result);
//
//        $this->setExpectedException('\SoapFault', 'Record with ID "' . $id . '" can not be found');
//        $this->soapClient->getIssue($id);
//    }
}
