<?php

declare(strict_types=1);

namespace oat\taoTrainingExt\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\oatbox\event\EventManager;
use oat\oatbox\reporting\Report;
use oat\tao\scripts\tools\migrations\AbstractMigration;
use oat\taoProctoring\model\event\DeliveryExecutionFinished;
use oat\taoTrainingExt\models\listeners\DeliveryExecutionFinishedHandler;
use oat\taoTrainingExt\scripts\install\RegisterDeliveryExecutionFinishedEventHandler;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202103031655322816_taoTrainingExt extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Register custom event handler for delivery finished event.';
    }

    public function up(Schema $schema): void
    {
        $action = new RegisterDeliveryExecutionFinishedEventHandler();
        $this->runAction($action);
    }

    public function down(Schema $schema): void
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->getServiceLocator()->get(EventManager::SERVICE_ID);
        $eventManager->detach(
            DeliveryExecutionFinished::class,
            [DeliveryExecutionFinishedHandler::class, 'createTrainingReport']
        );
        $this->getServiceManager()->register(EventManager::SERVICE_ID, $eventManager);

        $this->addReport(Report::createSuccess("Event handlers were successfully removed."));
    }
}
