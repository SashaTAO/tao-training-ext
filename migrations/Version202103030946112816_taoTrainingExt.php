<?php

declare(strict_types=1);

namespace oat\taoTrainingExt\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\oatbox\reporting\Report;
use oat\tao\scripts\tools\migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202103030946112816_taoTrainingExt extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Example of long running irreversible migration.';
    }

    public function up(Schema $schema): void
    {
        $this->addReport(
            new Report(
                Report::TYPE_WARNING,
                'Run `\oat\taoTrainingExt\scripts\db\AddReportBodyColumn` script to execute DB migrations.'
            )
        );
    }

    public function down(Schema $schema): void
    {
        // This Migration is "Irreversible".
        $this->throwIrreversibleMigrationException();
    }
}
