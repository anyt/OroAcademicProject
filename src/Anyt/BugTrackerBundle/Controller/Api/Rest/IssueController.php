<?php

namespace Anyt\BugTrackerBundle\Controller\Api\Rest;

use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("issue")
 * @NamePrefix("anyt_api_")
 */
class IssueController extends RestController implements ClassResourceInterface
{
    /**
     * REST GET list.
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )
     * @AclAncestor("anyt_issue_view")
     *
     * @return Response
     */
    public function cgetAction()
    {
        $page = (int) $this->getRequest()->get('page', 1);
        $limit = (int) $this->getRequest()->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }

    /**
     * REST GET item.
     *
     * @param string $id
     *
     * @ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("anyt_issue_view")
     *
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * REST PUT.
     *
     * @param int $id Issue item id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("anyt_issue_update")
     *
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * Create new issue.
     *
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("anyt_issue_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * REST DELETE.
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete Issue",
     *      resource=true
     * )
     * @Acl(
     *      id="anyt_issue_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * Get entity Manager.
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('anyt.issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('anyt.form.type.issue.api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('anyt.form.handler.issue.api');
    }
}
