<?php


namespace Anyt\BugTrackerBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issues_by_status/chart/{widget}",
     *      name="anyt_dashboard_issue_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("AnytBugTrackerBundle:Dashboard:issuesByStatus.html.twig")
     * @param $widget
     * @return array
     */
    public function issuesByStatusAction($widget)
    {
        $data = $this->getDoctrine()
            ->getRepository('AnytBugTrackerBundle:Issue')
            ->getIssuesByStatus(
                $this->get('oro_security.acl_helper'),
                10
            );

        if (!empty($data)) { // Translate labels
            /** @var TranslatorInterface $translator */
            $translator = $this->get('translator');
            $data = array_map(
                function ($item) use ($translator) {
                    $item['label'] = $translator->trans(
                        sprintf('anyt.bugtracker.issue.status.%s.label', $item['label'])
                    );

                    return $item;
                },
                $data
            );
        }

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($data)
            ->setOptions(
                [
                    'name' => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'label'],
                        'value' => ['field_name' => 'itemCount']
                    ]
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
