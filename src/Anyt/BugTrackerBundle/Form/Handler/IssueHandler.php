<?php

namespace Anyt\BugTrackerBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Anyt\BugTrackerBundle\Entity\Issue;

class IssueHandler implements TagHandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @param FormInterface $form
     * @param Request       $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Process form.
     *
     * @param Issue $entity
     *
     * @return bool True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), ['POST', 'PUT'], true)) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler.
     *
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
        $this->tagManager->saveTagging($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }
}
