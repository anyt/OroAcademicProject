<?php

namespace Anyt\BugTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IssueResolution.
 *
 * @ORM\Table(name="anyt_bt_issue_resolution")
 * @ORM\Entity
 */
class IssueResolution
{
    const TYPE_UNSEROLVED = 'unresolved';
    const TYPE_FIXED = 'fixed';
    const TYPE_WONT_FIX = 'wont_fix';
    const TYPE_DUPLICATE = 'duplicate';
    const TYPE_INCOMPLETE = 'incomplete';
    const TYPE_CANNOT_REPRODUCE = 'cannot_reproduce';
    const TYPE_DONE = 'done';
    const TYPE_WONT_DO = 'wont_do';

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="resolution")
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
     * @return IssueResolution
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
            self::TYPE_UNSEROLVED,
            self::TYPE_FIXED,
            self::TYPE_WONT_FIX,
            self::TYPE_DUPLICATE,
            self::TYPE_INCOMPLETE,
            self::TYPE_CANNOT_REPRODUCE,
            self::TYPE_DONE,
            self::TYPE_WONT_DO,
        ];
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return IssueResolution
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
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param ArrayCollection $issues
     *
     * @return IssueResolution
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;

        return $this;
    }
}
