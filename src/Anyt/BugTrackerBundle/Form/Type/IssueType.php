<?php

namespace Anyt\BugTrackerBundle\Form\Type;

use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', null, ['label' => 'anyt.bugtracker.issue.summary.label'])
            ->add('description', null, ['label' => 'anyt.bugtracker.issue.description.label'])
            ->add('assignee', 'oro_user_select', ['label' => 'anyt.bugtracker.issue.assignee.label'])
//          @todo can implement this after the search index only ->add('relatedIssues', 'oro_multiple_entity')
            ->add(
                'priority',
                'entity',
                [
                    'class' => 'Anyt\BugTrackerBundle\Entity\IssuePriority',
                    'property' => 'title'
                ]
            )
            ->add(
                'resolution',
                'entity',
                [
                    'class' => 'Anyt\BugTrackerBundle\Entity\IssueResolution',
                    'property' => 'title'
                ]
            )
            // tags
            ->add(
                'tags',
                'oro_tag_select',
                array(
                    'label' => 'oro.tag.entity_plural_label'
                )
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Issue $issue */
                $issue = $event->getData();
                $form = $event->getForm();
                $type = $issue->getType();

                if (null === $type || Issue::TYPE_SUBTASK !== $type) {
                    $form->add(
                        'type',
                        'choice',
                        [
                            'label' => 'anyt.bugtracker.issue.type.label',
                            'choices' => [
                                Issue::TYPE_BUG => 'anyt.bugtracker.issue.type.bug.label',
                                Issue::TYPE_TAKS => 'anyt.bugtracker.issue.type.task.label',
                                Issue::TYPE_STORY => 'anyt.bugtracker.issue.type.story.label',
                            ]
                        ]
                    );
                }
            }
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Anyt\BugTrackerBundle\Entity\Issue',
            )
        );
    }

    public function getName()
    {
        return 'anyt_issue';
    }
}