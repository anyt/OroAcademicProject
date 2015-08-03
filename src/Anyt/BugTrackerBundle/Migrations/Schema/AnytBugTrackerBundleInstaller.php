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
        $this->createAnytBugtrackerIssueTable($schema);
        $this->createAnytBugtrackerIssuePriorityTable($schema);
        $this->createAnytBugtrackerIssueResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addAnytBugtrackerIssueForeignKeys($schema);
    }

    /**
     * Create anyt_bt_issue table
     *
     * @param Schema $schema
     */
    protected function createAnytBugtrackerIssueTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 64]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('created', 'datetime', []);
        $table->addColumn('updated', 'datetime', []);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_reporter_id'], 'IDX_CD22DC248D42F60', []);
        $table->addIndex(['user_assignee_id'], 'IDX_CD22DC2F0F7B4F5', []);
        $table->addIndex(['priority_id'], 'IDX_CD22DC2497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_CD22DC212A1C43A', []);
    }

    /**
     * Create anyt_bt_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createAnytBugtrackerIssuePriorityTable(Schema $schema)
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
    protected function createAnytBugtrackerIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('anyt_bt_issue_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('title', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add anyt_bt_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addAnytBugtrackerIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('anyt_bt_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('anyt_bt_issue_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_reporter_id'],
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
            $schema->getTable('oro_user'),
            ['user_assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}
