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

    const  NAME = 'anyt_issue';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text', ['label' => 'anyt.bugtracker.issue.summary.label'])
            ->add('description', 'textarea', ['label' => 'anyt.bugtracker.issue.description.label'])
            ->add('assignee', 'oro_user_select', ['label' => 'anyt.bugtracker.issue.assignee.label'])
            ->add(
                'appendRelatedIssues',
                'oro_entity_identifier',
                [
                    'class'    => 'Anyt\BugTrackerBundle\Entity\Issue',
                    'required' => false,
                    'mapped'   => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'removeRelatedIssues',
                'oro_entity_identifier',
                [
                    'class'    => 'Anyt\BugTrackerBundle\Entity\Issue',
                    'required' => false,
                    'mapped'   => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'priority',
                'entity',
                [
                    'class' => 'Anyt\BugTrackerBundle\Entity\IssuePriority',
                    'property' => 'title',
                ]
            )
            ->add(
                'resolution',
                'entity',
                [
                    'class' => 'Anyt\BugTrackerBundle\Entity\IssueResolution',
                    'property' => 'title',
                ]
            )
            // tags
            ->add(
                'tags',
                'oro_tag_select',
                [
                    'label' => 'oro.tag.entity_plural_label',
                ]
            )
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Issue $issue */
                $issue = $event->getData();
                $form = $event->getForm();
                // display type field only for new issues without predefined type
                if (null === $issue || (null === $issue->getType() && null === $issue->getId())) {
                    $form->add(
                        'type',
                        'choice',
                        [
                            'label' => 'anyt.bugtracker.issue.type.label',
                            'choices' => [
                                Issue::TYPE_BUG => 'anyt.bugtracker.issue.type.bug.label',
                                Issue::TYPE_TAKS => 'anyt.bugtracker.issue.type.task.label',
                                Issue::TYPE_STORY => 'anyt.bugtracker.issue.type.story.label',
                            ],
                        ]
                    );
                }
            }
        );
    }

    /**
     * @param string $dataClass
     *
     * @return $this
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Anyt\BugTrackerBundle\Entity\Issue',
            ]
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return self::NAME;
    }
}
