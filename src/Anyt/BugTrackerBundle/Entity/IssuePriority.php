<?php

namespace Anyt\BugTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IssuePriority.
 *
 * @ORM\Table(name="anyt_bugtracker_issue_priority")
 * @ORM\Entity
 */
class IssuePriority
{
    const TYPE_BLOCKER = 'blocker';
    const TYPE_CRITICAL = 'critical';
    const TYPE_MAJOR = 'major';
    const TYPE_TRIVIAL = 'trivial';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="priority")
     **/
    private $issues;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return IssuePriority
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BLOCKER,
            self::TYPE_CRITICAL,
            self::TYPE_MAJOR,
            self::TYPE_TRIVIAL,
        ];
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return IssuePriority
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set weight.
     *
     * @param int $weight
     *
     * @return IssuePriority
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
