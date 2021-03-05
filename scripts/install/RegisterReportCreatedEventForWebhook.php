<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA ;
 */
declare(strict_types=1);

namespace oat\taoTrainingExt\scripts\install;


use oat\oatbox\event\EventManager;
use oat\oatbox\extension\InstallAction;
use oat\oatbox\reporting\Report;
use oat\tao\model\webhooks\WebhookEventsService;
use oat\taoTrainingExt\models\events\TrainingReportCreated;

class RegisterReportCreatedEventForWebhook extends InstallAction
{
    public function __invoke($params)
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->getServiceLocator()->get(EventManager::SERVICE_ID);
        /** @var WebhookEventsService $webhookEventService */
        $webhookEventService = $this->getServiceLocator()->get(WebhookEventsService::SERVICE_ID);
        $webhookEventService->registerEvent(TrainingReportCreated::EVENT_NAME);

        $this->getServiceManager()->register(WebhookEventsService::SERVICE_ID, $webhookEventService);
        $this->getServiceManager()->register(EventManager::SERVICE_ID, $eventManager);

        return Report::createSuccess("TrainingReportCreated webhook was successfully registered.");
    }
}
