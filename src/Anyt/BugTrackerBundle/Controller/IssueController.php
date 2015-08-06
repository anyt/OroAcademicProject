<?php

namespace Anyt\BugTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\Request;

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
     *
     * @param Issue $issue
     *
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return ['entity' => $issue];
    }

    /**
     * Create issue form.
     *
     * @Route("/create", name="anyt_issue_create")
     * @Acl(
     *      id="anyt_issue_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     * @Template("AnytBugTrackerBundle:Issue:update.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();

        if (null !== $parentId = $request->query->get('parent')) {
            $parent = $this->getDoctrine()->getManager()->find('AnytBugTrackerBundle:Issue', $parentId);

            if (!$parent || !$parent->isAllowedParent()) {
                return $this->createNotFoundException();
            }
            $issue
                ->setType(Issue::TYPE_SUBTASK)
                ->setParent($parent);
        }

        if (null !== $assigneeId = $request->query->get('assignee')) {
            $assignee = $this->getDoctrine()->getManager()->find('OroUserBundle:User', $assigneeId);

            if (!$assignee) {
                return $this->createNotFoundException();
            }
            $issue
                ->setAssignee($assignee);
        }
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('anyt_issue_create', $this->getRequest());

        return $this->update($issue, $formAction);
    }

    /**
     * Edit user form.
     *
     * @Route("/update/{id}", name="anyt_issue_update", requirements={"id"="\d+"})
     * @Acl(
     *      id="anyt_issue_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="AnytBugTrackerBundle:Issue"
     * )
     * @Template()
     *
     * @param Issue $entity
     *
     * @return array
     */
    public function updateAction(Issue $entity)
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('anyt_issue_update', $this->getRequest(), ['id' => $entity->getId()]);

        return $this->update($entity, $formAction);
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
            'entity_class' => 'Anyt/BugTrackerBundle/Entity/Issue',
        ];
    }

    /**
     * @param Issue  $entity
     * @param string $formAction
     *
     * @return array
     */
    protected function update(Issue $entity, $formAction)
    {
        $saved = false;

        $request = $this->getRequest();
        $form = $this->get('form.factory')->create('anyt_issue', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
            if (!$this->getRequest()->get('_widgetContainer')) {
                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'anyt_issue_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'anyt_issue_index'],
                    $entity
                );
            }
            $saved = true;
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
            'saved' => $saved,
            'formAction' => $formAction,
        ];
    }

    /**
     * @Route("/widget/grid/{id}", name="anyt_issue_children_widget_items", requirements={"id"="\d+"}))
     * @AclAncestor("anyt_issue_view")
     * @Template("AnytBugTrackerBundle:Issue/widget:children.html.twig")
     *
     * @param Issue $issue
     *
     * @return array
     */
    public function childrenAction(Issue $issue)
    {
        return ['entity' => $issue];
    }

    /**
     * @Route("/owner/{userId}", name="anyt_issue_owner_items", requirements={"userId"="\d+"})
     * @AclAncestor("anyt_issue_view")
     * @Template("AnytBugTrackerBundle:Issue/widget:ownerItems.html.twig")
     *
     * @param $userId
     *
     * @return array
     */
    public function ownerItemsAction($userId)
    {
        return ['userId' => $userId];
    }

    /**
     * @Route("/assignee/{userId}", name="anyt_issue_assignee_items", requirements={"userId"="\d+"})
     * @AclAncestor("anyt_issue_view")
     * @Template("AnytBugTrackerBundle:Issue/widget:assigneeItems.html.twig")
     *
     * @param $userId
     *
     * @return array
     */
    public function assigneeItemsAction($userId)
    {
        return ['userId' => $userId];
    }
}
