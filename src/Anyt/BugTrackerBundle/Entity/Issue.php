<?php

namespace Anyt\BugTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Anyt\BugTrackerBundle\Model\ExtendIssue;

/**
 * Issue.
 *
 * @ORM\Table(name="anyt_bugtracker_issue")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @Config
 *
 */
class Issue extends ExtendIssue implements Taggable
{
    const TYPE_BUG = 'bug';
    const TYPE_TAKS = 'task';
    const TYPE_STORY = 'story';
    const TYPE_SUBTASK = 'subtask';
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
     * @ORM\Column(name="summary", type="string", length=255)
     */
    private $summary;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=64)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_reporter_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_assignee_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $assignee;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="anyt_bugtracker_issue_to_collaborators",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $collaborators;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Issue")
     * @ORM\JoinTable(name="anyt_bugtracker_issue_to_related_issues",
     *      joinColumns={@ORM\JoinColumn(name="ticket_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $relatedIssues;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority", inversedBy="issues")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    protected $priority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution", inversedBy="issues")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     */
    protected $resolution;

    /**
     * @var ArrayCollection
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          }
     *      }
     * )
     */
    protected $tags;

    public function __construct()
    {
        parent::__construct();

        $this->collaborators = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
    }

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
     * Set summary.
     *
     * @param string $summary
     *
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param mixed $reporter
     *
     * @return Issue
     */
    public function setReporter(User $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     *
     * @return Issue
     */
    public function setAssignee(User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BUG,
            self::TYPE_TAKS,
            self::TYPE_STORY,
            self::TYPE_SUBTASK,
        ];
    }

    /**
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param User $collaborator
     *
     * @return Issue
     */
    public function addCollaborator(User $collaborator)
    {
        if (!$this->getCollaborators()->contains($collaborator)) {
            $this->getCollaborators()->add($collaborator);
        }

        return $this;
    }

    /**
     * @param ArrayCollection $collaborators
     *
     * @return Issue
     */
    public function setCollaborators(Collection $collaborators)
    {
        $this->collaborators = $collaborators;

        return $this;
    }

    /**
     * @param User $collaborator
     *
     * @return Issue
     */
    public function removeCollaborator(User $collaborator)
    {
        if ($this->getCollaborators()->contains($collaborator)) {
            $this->getCollaborators()->removeElement($collaborator);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * Add specified relatedIssue
     *
     * @param Issue $relatedIssue
     *
     * @return Issue
     */
    public function addRelatedIssue(Issue $relatedIssue)
    {
        if (!$this->getRelatedIssues()->contains($relatedIssue)) {
            $this->getRelatedIssues()->add($relatedIssue);
        }

        return $this;
    }

    /**
     * Set relatedIssues collection
     *
     * @param Collection $relatedIssues
     *
     * @return Issue
     */
    public function setRelatedIssues(Collection $relatedIssues)
    {
        $this->relatedIssues = $relatedIssues;

        return $this;
    }

    /**
     * Remove specified relatedIssue
     *
     * @param Issue $relatedIssue
     *
     * @return Issue
     */
    public function removeRelatedIssue(Issue $relatedIssue)
    {
        if ($this->getRelatedIssues()->contains($relatedIssue)) {
            $this->getRelatedIssues()->removeElement($relatedIssue);
        }

        return $this;
    }

    /**
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param IssuePriority $priority
     *
     * @return Issue
     */
    public function setPriority(IssuePriority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param IssueResolution $resolution
     *
     * @return Issue
     */
    public function setResolution(IssueResolution $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /** @ORM\PostPersist */
    public function doPostPersist()
    {
        $this->code = sprintf('ISSUE-%d', $this->id);
    }
}
