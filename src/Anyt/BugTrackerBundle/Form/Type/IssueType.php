<?php
/**
 * Created by PhpStorm.
 * User: anyt
 * Date: 8/3/15
 * Time: 3:20 PM
 */

namespace Anyt\BugTrackerBundle\Form\Type;

use Anyt\BugTrackerBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', null, ['label' => 'issue'])
            ->add('description', null, ['label' => 'issue'])
//            ->add('type') @todo
        ->add('assignee', 'oro_user_select', ['label'=>'' ])
//            ->add('relatedIssues', 'multiselect') @todo
->add('priority')
->add('resolution')
->add('tags')
        ;
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