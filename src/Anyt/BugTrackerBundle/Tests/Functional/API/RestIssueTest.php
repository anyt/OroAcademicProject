<?php

namespace Anyt\BugTrackerBundle\Tests\Functional\API;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class RestIssueTest extends WebTestCase
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
    }

    /**
     * @return array
     */
    public function testCreate()
    {
        $request = [
            'issue' => $this->issueCreateData
        ];

        $this->client->request(
            'POST',
            $this->getUrl('anyt_api_post_issue'),
            $request
        );
        $result = $this->getJsonResponseContent($this->client->getResponse(), 201);
        $this->assertArrayHasKey('id', $result);

        $request['id'] = $result['id'];

        return $request;
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testGet(array $request)
    {
        $this->client->request(
            'GET',
            $this->getUrl('anyt_api_get_issues')
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $id = $request['id'];
        $result = array_filter(
            $result,
            function ($a) use ($id) {
                return $a['id'] === $id;
            }
        );

        $this->assertNotEmpty($result);
        $this->assertEquals($request['issue']['summary'], reset($result)['summary']);

        $this->client->request(
            'GET',
            $this->getUrl('anyt_api_get_issue', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($request['issue']['summary'], $result['summary']);
    }

    /**
     * @param array $request
     * @depends testCreate
     * @depends testGet
     */
    public function testUpdate(array $request)
    {
        $request['issue']['summary'] .= '_Updated';
        $this->client->request(
            'PUT',
            $this->getUrl('anyt_api_put_issue', ['id' => $request['id']]),
            $request
        );
        $result = $this->client->getResponse();

        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request(
            'GET',
            $this->getUrl('anyt_api_get_issue', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals(
            $request['issue']['summary'],
            $result['summary']
        );
    }

    /**
     * @param array $request
     * @depends testCreate
     */
    public function testDelete(array $request)
    {
        $this->client->request(
            'DELETE',
            $this->getUrl('anyt_api_delete_issue', ['id' => $request['id']])
        );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);
        $this->client->request('GET', $this->getUrl('anyt_api_get_issue', ['id' => $request['id']]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
