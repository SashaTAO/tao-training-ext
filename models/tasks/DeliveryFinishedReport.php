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

namespace oat\taoTrainingExt\models\tasks;

use Exception;
use common_exception_MissingParameter;
use oat\oatbox\action\Action;
use oat\oatbox\log\LoggerAwareTrait;
use oat\oatbox\reporting\Report;
use oat\tao\model\taskQueue\Task\TaskAwareInterface;
use oat\tao\model\taskQueue\Task\TaskAwareTrait;
use oat\taoTrainingExt\models\reports\ReportFactory;
use oat\taoTrainingExt\models\reports\TrainingReportService;
use Psr\Log\LoggerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class DeliveryFinishedReport implements Action, ServiceLocatorAwareInterface, TaskAwareInterface, LoggerAwareInterface
{
    use TaskAwareTrait;
    use ServiceLocatorAwareTrait;
    use LoggerAwareTrait;

    public function __invoke($params)
    {
        if (!is_array($params) || empty($params)) {
            throw new common_exception_MissingParameter();
        }

        return $this->processDeliveryFinishedReport($params[0]);
    }

    private function processDeliveryFinishedReport(string $deliveryExecutionId): Report
    {
        try {
            /** @var ReportFactory $reportFactory */
            $reportFactory = $this->getServiceLocator()->get(ReportFactory::class);
            $trainingReport = $reportFactory->create($deliveryExecutionId);

            $this->getServiceLocator()
                ->get(TrainingReportService::SERVICE_ID)
                ->addNewReport($trainingReport);

            $report = Report::createSuccess("Training report was processed.");
        } catch (Exception $e) {
            // Do something here
            $this->logError(
                "Training report processing failed with error",
                [
                    "message" => $e->getMessage(),
                    "trace" => $e->getTrace(),
                ]
            );
            $report = Report::createError("Training report processing failed.");
        }

        return $report;
    }
}
