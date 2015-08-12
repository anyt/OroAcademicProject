<?php

namespace Anyt\BugTrackerBundle\Migrations\Data\Demo\ORM;

use Anyt\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadIssueEntityData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const ISSUES_COUNT = 20;

    /**
     * @var array
     */
    protected static $fixtureText = array(
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.',
        'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
        'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.',
        'Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet.',
        'Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi..',
        'Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra,',
        'Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel.',
        'Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.',
        'Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed.',
        'Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.',
        'Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus.',
        'Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales.',
        'Fusce vulputate eleifend sapien. Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.',
        'Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus.',
        'Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.',
        'Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent adipiscing.',
        'Vestibulum volutpat pretium libero. Cras id dui. Aenean ut eros et nisl sagittis vestibulum.',
        'Sed lectus. Donec mollis hendrerit risus. Phasellus nec sem in justo pellentesque facilisis. Etiam imperdiet.',
        'Phasellus leo dolor, tempus non, auctor et, hendrerit quis, nisi. Curabitur ligula sapien, tincidunt non.',
        'Praesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate arcu.',
    );

    /**
     * @todo
     *
     * @var array
     */
    protected static $relatedEntities = array(
        'AnytBugTrackerBundle:Contact' => 'setRelatedContact',
        'AnytBugTrackerBundle:Account' => 'setRelatedAccount',
    );

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var array
     */
    protected $entitiesCount;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return array(
            'Anyt\BugTrackerBundle\Migrations\Data\Demo\ORM\LoadUserData',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->entityManager = $manager;
        $this->organization = $this->getReference('default_organization');

        for ($i = 0; $i < self::ISSUES_COUNT; ++$i) {
            //            $summary = self::$fixtureText[$i];
//
//            if ($manager->getRepository('AnytBugTrackerBundle:IssueEntity')->findOneBySummary($summary)) {
//                // Case with this title is already exist
//                continue;
//            }

            $issue = $this->createIssueEntity();
            $this->entityManager->persist($issue);
        }

        $manager->flush();
    }

    /**
     * @param string $subject
     *
     * @return Issue|null
     */
    protected function createIssueEntity()
    {
        $priority = $this->getRandomEntity('AnytBugTrackerBundle:IssuePriority');
        $resolution = $this->getRandomEntity('AnytBugTrackerBundle:IssueResolution');
        $owner = $this->getRandomEntity('OroUserBundle:User');
        $assignee = $this->getRandomEntity('OroUserBundle:User');

        $types = [
            Issue::TYPE_BUG,
            Issue::TYPE_TAKS,
            Issue::TYPE_STORY,
            Issue::TYPE_SUBTASK,
        ];
        $issue = new Issue();

        $issue
            ->setSummary($this->getRandomText())
            ->setDescription($this->getRandomText())
            ->setType($types[array_rand($types)])
            ->setPriority($priority)
            ->setResolution($resolution)
            ->setOwner($owner)
            ->setAssignee($assignee)
            ->setOrganization($this->organization);

        return $issue;
    }

    /**
     * @param string $entityName
     *
     * @return object|null
     */
    protected function getRandomEntity($entityName)
    {
        $count = $this->getEntityCount($entityName);

        if ($count) {
            return $this->entityManager->createQueryBuilder()
                ->select('e')
                ->from($entityName, 'e')
                ->setFirstResult(rand(0, $count - 1))
                ->setMaxResults(1)
                ->orderBy('e.'.$this->entityManager->getClassMetadata($entityName)->getSingleIdentifierFieldName())
                ->getQuery()
                ->getSingleResult();
        }

        return;
    }

    /**
     * @param string $entityName
     *
     * @return int
     */
    protected function getEntityCount($entityName)
    {
        if (!isset($this->entitiesCount[$entityName])) {
            $this->entitiesCount[$entityName] = (int) $this->entityManager->createQueryBuilder()
                ->select('COUNT(e)')
                ->from($entityName, 'e')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $this->entitiesCount[$entityName];
    }

    /**
     * @return \DateTime
     */
    protected function getRandomDate()
    {
        $result = new \DateTime();
        $result->sub(new \DateInterval(sprintf('P%dDT%dM', rand(0, 30), rand(0, 1440))));

        return $result;
    }

    /**
     * @return string
     */
    protected function getRandomText()
    {
        return self::$fixtureText[rand(0, count(self::$fixtureText) - 1)];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
