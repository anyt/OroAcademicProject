<?php

namespace Anyt\BugTrackerBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Oro\Bundle\SoapBundle\Entity\SoapEntityInterface;

/**
 * @Soap\Alias("Anyt.BugTrackerBundle.Entity.Issue")
 */
class IssueSoap extends Issue implements SoapEntityInterface
{
    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $id;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $summary;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $code;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $description;


    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $created;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $updated;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $type;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $status;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $assignee;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $priority;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $resolution;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $owner;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $parent;

    /**
     * @param Issue $case
     */
    public function soapInit($case)
    {
        $this->id = $case->getId();
        $this->summary = $case->getSummary();
        $this->code = $case->getCode();
        $this->description = $case->getDescription();
        $this->created = $case->getCreated();
        $this->updated = $case->getUpdated();
        $this->type = $case->getType();
        $this->status = $case->getStatus();

        $this->assignee = $this->getEntityId($case->getAssignee());
        $this->priority = $this->getEntityId($case->getPriority());
        $this->resolution = $this->getEntityId($case->getResolution());
        $this->owner = $this->getEntityId($case->getOwner());
        $this->parent = $this->getEntityId($case->getParent());
    }

    /**
     * @param object $entity
     *
     * @return integer|null
     */
    protected function getEntityId($entity)
    {
        if ($entity) {
            return $entity->getId();
        }

        return null;
    }
}
