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

use JsonSerializable;

class TrainingReport implements JsonSerializable
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';

    private $deliveryId;
    private $deliveryExecutionId;
    private $reportBody;
    private $reportStatus;

    public function __construct(string $deliveryId, string $deliveryExecutionId, string $reportStatus, array $reportBody)
    {
        $this->deliveryId = $deliveryId;
        $this->deliveryExecutionId = $deliveryExecutionId;
        $this->reportStatus = $reportStatus;
        $this->reportBody = $reportBody;
    }

    public function getDeliveryId(): string
    {
        return $this->deliveryId;
    }

    public function getDeliveryExecutionId(): string
    {
        return $this->deliveryExecutionId;
    }

    public function getReportBody(): array
    {
        return $this->reportBody;
    }

    public function getReportStatus(): string
    {
        return $this->reportStatus;
    }

    public function setReportStatus(string $status): void
    {
        $this->reportStatus = $status;
    }

    public function jsonSerialize(): array
    {
        return [
            'delivery_id' => $this->deliveryId,
            'delivery_execution_id' => $this->deliveryExecutionId,
            'report_body' => $this->reportBody
        ];
    }
}
