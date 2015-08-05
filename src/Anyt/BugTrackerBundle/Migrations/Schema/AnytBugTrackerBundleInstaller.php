<?php

namespace Anyt\BugTrackerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class AnytBugTrackerBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createAnytBtIssueTable($schema);
        $this->createAnytBtIssuePriorityTable($schema);
        $this->createAnytBtIssueResolutionTable($schema);
        $this->createAnytBtIssueToCollaboratorsTable($schema);
        $this->createAnytBtIssueToIssuesTable($schema);

        /** Foreign keys generation **/
        $this->addAnytBtIssueForeignKeys($schema);
        $this->addAnytBtIssueToCollaboratorsForeignKeys($schema);
        $this->addAnytBtIssueToIssuesForeignKeys($schema);
    }

    /**
     * Create anyt_bt_issue table
     *
     * @param Schema $schema
     */
    protected function createAnytBtIssueTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['notnull' => false, 'length' => 64]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('created', 'datetime', []);
        $table->addColumn('updated', 'datetime', []);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_owner_id'], 'IDX_CD22DC248D42F60', []);
        $table->addIndex(['user_assignee_id'], 'IDX_CD22DC2F0F7B4F5', []);
        $table->addIndex(['priority_id'], 'IDX_CD22DC2497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_CD22DC212A1C43A', []);
        $table->addIndex(['organization_id'], 'IDX_69EA008332C8A3DE', []);
        $table->addIndex(['parent_id'], 'IDX_69EA0083727ACA70', []);
    }

    /**
     * Create anyt_bt_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createAnytBtIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('title', 'string', ['length' => 255]);
        $table->addColumn('weight', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create anyt_bt_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createAnytBtIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('title', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create anyt_bt_issue_to_collaborators table
     *
     * @param Schema $schema
     */
    protected function createAnytBtIssueToCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue_to_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_B43D2765E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_B43D276A76ED395', []);
    }

    /**
     * Create anyt_bt_issue_to_issues table
     *
     * @param Schema $schema
     */
    protected function createAnytBtIssueToIssuesTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue_to_issues');
        $table->addColumn('ticket_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['ticket_id', 'user_id']);
        $table->addIndex(['ticket_id'], 'IDX_A398D88C700047D2', []);
        $table->addIndex(['user_id'], 'IDX_A398D88CA76ED395', []);
    }

    /**
     * Add anyt_bt_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addAnytBtIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('anyt_bt_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_owner_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Add anyt_bt_issue_to_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addAnytBtIssueToCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('anyt_bt_issue_to_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add anyt_bt_issue_to_issues foreign keys.
     *
     * @param Schema $schema
     */
    protected function addAnytBtIssueToIssuesForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('anyt_bt_issue_to_issues');
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue'),
            ['ticket_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
