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
     * @return array
     */
    public function testCreate()
    {

        $request = $this->issueCreateData;
        $result = $this->soapClient->createIssue($request);
        $this->assertTrue((bool)$result, $this->soapClient->__getLastResponse());

        $request['id'] = $result;

        return $request;
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testGet(array $request)
    {
        $issues = $this->soapClient->getIssues(1, 1000);
        $issues = $this->valueToArray($issues);
        $issueName = $request['summary'];
        $issue = $issues['item'];
        if (isset($issue[0])) {
            $issue = array_filter(
                $issue,
                function ($a) use ($issueName) {
                    return $a['summary'] == $issueName;
                }
            );
            $issue = reset($issue);
        }

        $this->assertEquals($request['summary'], $issue['summary']);
        $this->assertEquals($request['id'], $issue['id']);
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testUpdate(array $request)
    {
        $issueUpdate = $request;
        unset($issueUpdate['id']);
        $issueUpdate['summary'] .= '_Updated';

        $result = $this->soapClient->updateIssue($request['id'], $issueUpdate);
        $this->assertTrue($result);

        $issue = $this->soapClient->getIssue($request['id']);
        $issue = $this->valueToArray($issue);

        $this->assertEquals($issueUpdate['summary'], $issue['summary']);

        return $request;
    }

    /**
     * @param array $request
     * @depends testUpdate
     */
    public function testDelete(array $request)
    {
        $result = $this->soapClient->deleteIssue($request['id']);
        $this->assertTrue($result);

        $this->setExpectedException('\SoapFault', 'Record with ID "' . $request['id'] . '" can not be found');
        $this->soapClient->getIssue($request['id']);
    }
}
