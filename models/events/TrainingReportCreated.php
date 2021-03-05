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

namespace oat\taoTrainingExt\models\events;

use oat\tao\model\webhooks\WebhookSerializableEventInterface;
use oat\taoTrainingExt\models\reports\TrainingReport;

class TrainingReportCreated implements WebhookSerializableEventInterface
{
    const EVENT_NAME = self::class;
    const WEBHOOK_EVENT_NAME = 'TrainingReportCreated';

    private $reportId;
    private $report;

    public function __construct(int $reportId, TrainingReport $report)
    {
        $this->reportId = $reportId;
        $this->report = $report;
    }

    public function getWebhookEventName(): string
    {
        return self::WEBHOOK_EVENT_NAME;
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    public function serializeForWebhook(): array
    {
        return [
            'report_id' => $this->reportId,
            'report' => $this->report
        ];
    }
}
