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

use oat\oatbox\extension\InstallAction;
use oat\oatbox\reporting\Report;
use oat\oatbox\service\ConfigurableService;
use oat\tao\model\webhooks\WebhookFileRegistry;
use oat\tao\model\webhooks\WebhookRegistryInterface;
use oat\taoTrainingExt\models\events\TrainingReportCreated;

class ConfigureReportCreatedWebhook extends InstallAction
{
    public function __invoke($params)
    {
        /** @var WebhookRegistryInterface|ConfigurableService $whRegistry */
        $whRegistry = $this->getServiceLocator()->get(WebhookRegistryInterface::SERVICE_ID);
        $webhookId = 'training-report-created';

        $config = $whRegistry->getOptions();
        $config[WebhookFileRegistry::OPTION_EVENTS][TrainingReportCreated::EVENT_NAME][] = $webhookId;
        $config[WebhookFileRegistry::OPTION_WEBHOOKS][$webhookId] = [
            'id' => 'training-report-webhook',
            'retryMax' => 5,
            'url' => 'xxxxxxxxxxxxxxxxxxxx',
            'httpMethod' => 'POST',
            'responseValidation' => false,
            'auth' => [
                'authClass' => 'oat\\tao\\model\\auth\\BasicAuthType',
                'credentials' => [
                    'xxxxxxxxxxxx' => 'yyyyyyyyyyyyy'
                ]
            ]
        ];
        $whRegistry->setOptions($config);

        $this->getServiceManager()->register(WebhookRegistryInterface::SERVICE_ID, $whRegistry);

        return Report::createSuccess("Webhook was successfully configured");
    }
}
