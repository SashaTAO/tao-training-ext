<?php

declare(strict_types=1);

namespace oat\taoTrainingExt\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\oatbox\event\EventManager;
use oat\oatbox\reporting\Report;
use oat\tao\model\webhooks\WebhookEventsService;
use oat\tao\model\webhooks\WebhookFileRegistry;
use oat\tao\model\webhooks\WebhookRegistryInterface;
use oat\tao\scripts\tools\migrations\AbstractMigration;
use oat\taoTrainingExt\models\events\TrainingReportCreated;
use oat\taoTrainingExt\scripts\install\ConfigureReportCreatedWebhook;
use oat\taoTrainingExt\scripts\install\RegisterReportCreatedEventForWebhook;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202103041654202816_taoTrainingExt extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Add custom event and configure webhook for this event.';
    }

    public function up(Schema $schema): void
    {
        $this->runAction(new RegisterReportCreatedEventForWebhook());
        $this->runAction(new ConfigureReportCreatedWebhook());
    }

    public function down(Schema $schema): void
    {
        // Unregister webhook event.
        $eventManager = $this->getServiceLocator()->get(EventManager::SERVICE_ID);
        /** @var WebhookEventsService $webhookEventService */
        $webhookEventService = $this->getServiceLocator()->get(WebhookEventsService::SERVICE_ID);
        $webhookEventService->unregisterEvent(TrainingReportCreated::EVENT_NAME);

        $this->getServiceManager()->register(WebhookEventsService::SERVICE_ID, $webhookEventService);
        $this->getServiceManager()->register(EventManager::SERVICE_ID, $eventManager);

        // Delete webhook from registry
        $whRegistry = $this->getServiceLocator()->get(WebhookRegistryInterface::SERVICE_ID);
        $config = $whRegistry->getOptions();
        unset($config[WebhookFileRegistry::OPTION_EVENTS][TrainingReportCreated::EVENT_NAME]);
        unset($config[WebhookFileRegistry::OPTION_WEBHOOKS]['training-report-created']);

        $this->addReport(Report::createSuccess('Event and webhook were deleted.'));
    }
}
