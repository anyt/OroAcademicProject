<?php

namespace Anyt\BugTrackerBundle\Tests\Functional;

use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\DomCrawler\Form;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ControllersTest extends WebTestCase
{

    /**
     * @var array
     */
    protected $issueCreateData = [

        'anyt_issue[summary]' => 'Issue_summary',
        'anyt_issue[description]' => 'test',
        'anyt_issue[type]' => 'story',
        'anyt_issue[assignee]' => 1,
        'anyt_issue[owner]' => 1,
        'anyt_issue[priority]' => 1,
        'anyt_issue[resolution]' => 1

    ];

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
        $form->setValues($this->issueCreateData);

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
    public function testCreateSubtask($id)
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('anyt_issue_view', ['id' => $id])
        );
        $link = $crawler->selectLink('Create Subtask')->link();

        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Save and Close')->form();

        $formData = $this->issueCreateData;

        $formData['anyt_issue[summary]'] = 'Subtask_summary';
        unset($formData['anyt_issue[type]']);
        $form->setValues($formData);

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue saved.', $crawler->html());
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
}
