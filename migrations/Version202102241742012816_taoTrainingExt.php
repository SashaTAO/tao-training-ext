<?php

declare(strict_types=1);

namespace oat\taoTrainingExt\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\tao\scripts\tools\migrations\AbstractMigration;
use oat\taoTrainingExt\models\reports\TrainingReportService;
use oat\taoTrainingExt\scripts\db\CreateTrainingReportTable;
use oat\taoTrainingExt\scripts\db\DropTrainingReportTable;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202102241742012816_taoTrainingExt extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Create new ';
    }

    public function up(Schema $schema): void
    {
        $this->getServiceManager()->register(
            TrainingReportService::SERVICE_ID,
            new TrainingReportService([
                TrainingReportService::OPTION_PERSISTENCE => 'default'
            ])
        );

        $this->runAction(new CreateTrainingReportTable());
    }

    public function down(Schema $schema): void
    {
        $this->runAction(new DropTrainingReportTable());
        $this->getServiceManager()->unregister(TrainingReportService::SERVICE_ID);
    }
}
