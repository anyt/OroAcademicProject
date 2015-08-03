<?php
/**
 * Created by PhpStorm.
 * User: anyt
 * Date: 8/3/15
 * Time: 3:20 PM
 */

namespace Anyt\BugTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Anyt\BugTrackerBundle\Entity\Issue',
        ));
    }

    public function getName()
    {
        return 'anyt_issue';
    }
}