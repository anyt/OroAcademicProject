<?php

namespace Anyt\BugTrackerBundle\Entity;

use Anyt\BugTrackerBundle\Exception\IssueTypeNotAllowedException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EmailBundle\Entity\EmailOwnerInterface;
use Oro\Bundle\EmailBundle\Model\EmailHolderInterface;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Anyt\BugTrackerBundle\Model\ExtendIssue;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Issue.
 *
 * @ORM\Table(name="anyt_bt_issue")
 * @ORM\Entity(repositoryClass="Anyt\BugTrackerBundle\Entity\Repository\IssueRepository")
 * @Config(
 *      routeName="anyt_issue_index",
 *      routeView="anyt_issue_view",
 *      defaultValues={
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "entity"={
 *              "icon"="icon-list-alt",
 *              "context-grid"="issues-for-context-grid"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="user_owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL"
 *          },
 *          "workflow"={
 *              "active_workflow"="issue_status_flow"
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue implements Taggable, EmailHolderInterface
{
    const TYPE_BUG = 'bug';
    const TYPE_TAKS = 'task';
    const TYPE_STORY = 'story';
    const TYPE_SUBTASK = 'subtask';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';
    const STATUS_REOPENED = 'reopened';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=10
     *          }
     *      }
     * )
     */
    private $summary;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=64, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=20,
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=30
     *          }
     *      }
     * )
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=40
     *          }
     *      }
     * )
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=50
     *          }
     *      }
     * )
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_assignee_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=60,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $assignee;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="anyt_bt_issue_to_collaborators",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=70,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $collaborators;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Issue")
     * @ORM\JoinTable(name="anyt_bt_issue_to_issues",
     *      joinColumns={@ORM\JoinColumn(name="issue_from_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="issue_to_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=80,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $relatedIssues;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority", inversedBy="issues")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=90,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $priority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution", inversedBy="issues")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=100,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $resolution;

    /**
     * @var ArrayCollection
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          },
     *          "importexport"={
     *              "order"=110,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $tags;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_owner_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=120,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=130,
     *              "full"=false
     *          }
     *      }
     * )
     */
    protected $organization;

    /**
     * @var ArrayCollection|Issue[]
     *
     * @ORM\OneToMany(targetEntity="Anyt\BugTrackerBundle\Entity\Issue", mappedBy="parent", cascade={"remove"})
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $children;

    // ...
    /**
     * @ORM\ManyToOne(targetEntity="Anyt\BugTrackerBundle\Entity\Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=140,
     *              "full"=false
     *          }
     *      }
     * )
     **/
    private $parent;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->status = self::STATUS_OPEN;
        $this->collaborators = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     *
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
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     *
     * @return Issue
     */
    public function setAssignee($assignee)
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
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
     * @param Collection $collaborators
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
     * Add specified relatedIssue.
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
     * Set relatedIssues collection.
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
     * Remove specified relatedIssue.
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
    public function setPriority($priority)
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
    public function setResolution($resolution)
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

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     *
     * @return Issue
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Set organization.
     *
     * @param Organization $organization
     *
     * @return Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization.
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return bool
     */
    public function isAllowedParent()
    {
        return in_array($this->getType(), self::getAllowedParents(), true);
    }

    /**
     * @return array
     */
    public static function getAllowedParents()
    {
        return [self::TYPE_STORY];
    }

    /**
     * @return bool
     */
    public function isAllowedChild()
    {
        return in_array($this->getType(), self::getAllowedChildren(), true);
    }

    /**
     * @return array
     */
    public static function getAllowedChildren()
    {
        return [self::TYPE_SUBTASK];
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Issue $child
     *
     * @return Issue
     */
    public function addChildren(Issue $child)
    {
        if (!$child->isAllowedChild()) {
            throw new IssueTypeNotAllowedException('Subtasks allowed only for stories', 500);
        }
        if (!$this->getChildren()->contains($child)) {
            $this->getChildren()->add($child);
        }

        return $this;
    }

    /**
     * @param ArrayCollection|Issue[] $children
     */
    public function setChildren($children)
    {
        foreach ($children as $child) {
            if (!$child->isAllowedChild()) {
                throw new IssueTypeNotAllowedException('Subtasks allowed only for stories', 500);
            }
        }

        $this->children = $children;
    }

    /**
     * @param Issue $issue
     *
     * @return Issue
     */
    public function removeChildren(Issue $issue)
    {
        if ($this->getChildren()->contains($issue)) {
            $this->getChildren()->removeElement($issue);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent(Issue $parent)
    {
        if (!$parent->isAllowedParent()) {
            throw new IssueTypeNotAllowedException('Subtasks allowed only for stories', 500);
        }
        $this->parent = $parent;
    }

    /**
     * Gets an email address which can be used to send messages
     *
     * @return string
     */
    public function getEmail()
    {
            return $this->assignee->getEmail();
    }
}
