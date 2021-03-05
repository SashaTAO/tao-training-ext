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

namespace oat\taoTrainingExt\models\listeners;

use Exception;
use oat\oatbox\service\ConfigurableService;
use oat\tao\model\taskQueue\QueueDispatcherInterface;
use oat\taoProctoring\model\event\DeliveryExecutionFinished;
use oat\taoTrainingExt\models\tasks\DeliveryFinishedReport;

class DeliveryExecutionFinishedHandler extends ConfigurableService
{
    public function createTrainingReport(DeliveryExecutionFinished $event): void
    {
        try {
            /** @var QueueDispatcherInterface $queueDispatcher reference_id */
            $queueDispatcher = $this->getServiceLocator()->get(QueueDispatcherInterface::SERVICE_ID);

            $taskTitle = "Report finished delivery execution to XXX";
            $queueDispatcher->createTask(
                new DeliveryFinishedReport(),
                [$event->getDeliveryExecution()->getIdentifier()],
                $taskTitle
            );
        } catch (Exception $e) {
            $this->logError("Cannot create a task 'DeliveryFinishedReport'");
        }
    }
}
