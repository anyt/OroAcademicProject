<?php

namespace Anyt\BugTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\Request;
use Anyt\BugTrackerBundle\Exception\IssueTypeNotAllowedException;

class IssueController extends Controller
{
    /**
     * @Route("/view/{id}", name="anyt_issue_view", requirements={"id"="\d+"})
     * @Acl(
     *      id="anyt_issue_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     * @Template()
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }

    /**
     * Create issue form
     *
     * @Route("/create/{parent}", name="anyt_issue_create", defaults={"parent"= false})
     * @Acl(
     *      id="anyt_issue_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     * @Template("AnytBugTrackerBundle:Issue:update.html.twig")
     */
    public function createAction(Issue $parent = null)
    {
        if (null !== $parent) {
            if (!$parent->isAllowedParent()) {
                return $this->createNotFoundException();
            }
            $issue = new Issue();
            $issue
                ->setType(Issue::TYPE_SUBTASK)
                ->setParent($parent);
        } else {
            $issue = new Issue();
        }

        return $this->update($issue);
    }

    /**
     * Edit user form
     *
     * @Route("/update/{id}", name="anyt_issue_update", requirements={"id"="\d+"})
     * @Acl(
     *      id="anyt_issue_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     * @Template()
     */
    public function updateAction(Issue $entity)
    {
        return $this->update($entity);
    }

    /**
     * @Route(
     *      "/{_format}",
     *      name="anyt_issue_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @AclAncestor("anyt_issue_view")
     * @Template
     */
    public function indexAction()
    {
        return [
            'entity_class' => 'Anyt/BugTrackerBundle/Entity/Issue'
        ];
    }

    /**
     * @param Issue $entity
     * @return array
     */
    protected function update(Issue $entity)
    {
        $request = $this->getRequest();
        $form = $this->get('form.factory')->create('anyt_issue', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'anyt_issue_update',
                    'parameters' => array('id' => $entity->getId()),
                ),
                array('route' => 'anyt_issue_index'),
                $entity
            );
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/widget/grid/{id}", name="anyt_issue_children_widget_items", requirements={"id"="\d+"}))
     * @AclAncestor("anyt_issue_view")
     * @Template("AnytBugTrackerBundle:Issue/widget:children.html.twig")
     * @param Issue $issue
     * @return array
     */
    public function childrenAction(Issue $issue)
    {
        return ['entity' => $issue];
    }
}
