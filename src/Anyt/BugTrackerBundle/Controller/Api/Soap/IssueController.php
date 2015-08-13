<?php

namespace Anyt\BugTrackerBundle\Controller\Api\Soap;

use Symfony\Component\Form\FormInterface;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Controller\Api\Soap\SoapController;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;

class IssueController extends SoapController
{
    /**
     * @Soap\Method("getIssues")
     * @Soap\Param("page", phpType="int")
     * @Soap\Param("limit", phpType="int")
     * @Soap\Result(phpType = "Anyt\BugTrackerBundle\Entity\IssueSoap[]")
     * @param int $page
     * @param int $limit
     * @return mixed|\Oro\Bundle\SoapBundle\Entity\SoapEntityInterface|\Traversable
     */
    public function cgetAction($page = 1, $limit = 10)
    {
        return $this->handleGetListRequest($page, $limit);
    }

    /**
     * @Soap\Method("getIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "Anyt\BugTrackerBundle\Entity\IssueSoap")
     * @param $id
     * @return mixed|object|\Oro\Bundle\SoapBundle\Entity\SoapEntityInterface
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * @Soap\Method("createIssue")
     * @Soap\Param("issue", phpType = "Anyt\BugTrackerBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "int")
     * @return int
     */
    public function createAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * @Soap\Method("updateIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Param("issue", phpType = "Anyt\BugTrackerBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "boolean")
     * @param $id
     * @return bool
     */
    public function updateAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * @Soap\Method("deleteIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "boolean")
     * @param $id
     * @return bool
     * @throws \SoapFault
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->container->get('anyt.issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->container->get('anyt.form.issue.api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->container->get('anyt.form.handler.issue.api');
    }
}
