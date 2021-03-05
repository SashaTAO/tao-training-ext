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

namespace oat\taoTrainingExt\models\reports;

use Exception;
use stdClass;
use oat\oatbox\service\ConfigurableService;
use oat\taoDelivery\model\execution\DeliveryExecutionInterface;
use oat\taoProctoring\model\execution\DeliveryExecutionManagerService;
use oat\taoResultServer\models\classes\ResultServerService;

class ReportFactory extends ConfigurableService
{
    public function create(string $deliveryExecutionId): TrainingReport
    {
        /** @var DeliveryExecutionManagerService $executionManager */
        $executionManager = $this->getServiceLocator()->get(DeliveryExecutionManagerService::SERVICE_ID);
        $deliveryExecution = $executionManager->getDeliveryExecutionById($deliveryExecutionId);
        $delivery = $deliveryExecution->getDelivery();
        $reportBody = $this->getDeliveryExecutionResultsReport($deliveryExecution);

        return new TrainingReport(
            $delivery->getUri(),
            $deliveryExecutionId,
            TrainingReport::STATUS_CREATED,
            $reportBody
        );
    }

    private function getDeliveryExecutionResultsReport(DeliveryExecutionInterface $deliveryExecution): array
    {
        $results = [];
        try {
            /** @var ResultServerService $resultService */
            $resultService = $this->getServiceLocator()->get(ResultServerService::SERVICE_ID);
            $resultStorage = $resultService->getResultStorage();
            $variables = $resultStorage->getDeliveryVariables($deliveryExecution->getIdentifier());
            foreach ($variables as $variableId => $groupedVariable) {
                foreach ($groupedVariable as $variableObj) {
                    if ($variableObj->callIdTest !== null) {
                        $results['test_variables'][$variableId][] = $this->parseTestVariable($variableObj);
                    } else {
                        $results['item_variables'][$variableId][] = $this->parseItemVariable($variableObj);
                    }
                }
            }
        } catch (Exception $e) {
            // Do something here.
        }

        return $results;
    }

    private function parseTestVariable(stdClass $variableObj): array
    {
        return [
            "test" => $variableObj->test,
            "identifier" => $variableObj->variable->getIdentifier(),
            "value" => $variableObj->variable->getValue(),
            "epoch" => $variableObj->variable->getEpoch(),
        ];
    }

    private function parseItemVariable(stdClass $variableObj): array
    {
        return [
            "item" => $variableObj->item,
            "identifier" => $variableObj->variable->getIdentifier(),
            "value" => $variableObj->variable->getValue(),
            "epoch" => $variableObj->variable->getEpoch(),
        ];
    }
}
