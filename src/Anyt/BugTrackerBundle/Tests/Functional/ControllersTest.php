<?php

namespace Anyt\BugTrackerBundle\Tests\Functional;

use Symfony\Component\DomCrawler\Form;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ControllersTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(
            [],
            array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1])
        );
    }

    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('anyt_issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('anyt_issue_create'));
        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();
        $form['anyt_issue[summary]'] = 'Issue_summary';
        $form['anyt_issue[description]'] = 'test';
        $form['anyt_issue[type]'] = 'story';
        $form['anyt_issue[assignee]'] = 1;
        $form['anyt_issue[owner]'] = 1;
        $form['anyt_issue[priority]'] = 1;
        $form['anyt_issue[resolution]'] = 1;

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue saved.', $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'issues-grid',
            ['issues-grid[_filter][summary][value]' => 'Issue_summary']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $id = $result['id'];
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('anyt_issue_update', ['id' => $result['id']])
        );
        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();
        $form['anyt_issue[summary]'] = 'Issue_summary_update';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue saved', $crawler->html());

        return $id;
    }

    /**
     * @depends testUpdate
     * @param $id
     */
    public function testView($id)
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('anyt_issue_view', ['id' => $id])
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue_summary_update', $crawler->html());
    }

    /**
     * @depends testUpdate
     * @param $id
     */
    public function testDelete($id)
    {
        $this->client->request(
            'DELETE',
            $this->getUrl('anyt_api_delete_issue', ['id' => $id])
        );

        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request(
            'GET',
            $this->getUrl('anyt_issue_view', ['id' => $id])
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 404);
    }


    public function testChildrenAction()
    {
        $this->markTestIncomplete();

    }

    public function testCollaboratorsAction()
    {
        $this->markTestIncomplete();

    }


    public function testOwnerItemsAction()
    {
        $this->markTestIncomplete();

    }

    public function testAssigneeItemsAction()
    {
        $this->markTestIncomplete();

    }

    public function testRecentAction()
    {
        $this->markTestIncomplete();

    }
}
