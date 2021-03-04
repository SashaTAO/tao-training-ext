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

namespace oat\taoTrainingExt\scripts\db;

use Exception;
use common_persistence_SqlPersistence;
use Doctrine\DBAL\Types\Types;
use oat\oatbox\extension\AbstractAction;
use oat\oatbox\reporting\Report;
use oat\taoTrainingExt\models\reports\TrainingReportService;

class CreateTrainingReportTable extends AbstractAction
{
    public function __invoke($params)
    {
        /** @var common_persistence_SqlPersistence $persistence */
        $persistence = $this->getServiceLocator()
            ->get(TrainingReportService::SERVICE_ID)
            ->getPersistence();

        return $this->createTable($persistence);
    }

    private function createTable(common_persistence_SqlPersistence $persistence): Report
    {
        try {
            $schema = $persistence->getSchemaManager()->createSchema();
            $fromSchema = clone $schema;

            // Perform changes.
            $table = $schema->createTable(TrainingReportService::TABLE_NAME);
            $table->addColumn(TrainingReportService::ID_COLUMN,
                              Types::INTEGER,
                              ['autoincrement' => true]
            );
            $table->addColumn(
                TrainingReportService::DELIVERY_ID_COLUMN,
                Types::STRING,
                ['length' => 255]
            );
            $table->addColumn(
                TrainingReportService::DELIVERY_EXECUTION_ID_COLUMN,
                Types::STRING,
                ['length' => 255]
            );
            $table->addColumn(
                TrainingReportService::REPORT_STATUS_COLUMN,
                Types::STRING,
                ['length' => 255]
            );

            $table->setPrimaryKey([TrainingReportService::ID_COLUMN]);
            $table->addUniqueIndex(
                [TrainingReportService::DELIVERY_ID_COLUMN, TrainingReportService::DELIVERY_EXECUTION_ID_COLUMN],
                'idx_delivery_id_delivery_execution_id'
            );
            $table->addIndex([TrainingReportService::DELIVERY_EXECUTION_ID_COLUMN]);

            // Execute schema transformation.
            $persistence->getPlatForm()->migrateSchema($fromSchema, $schema);
            $successMsg = '`training_reports` table was successfully created.';
            $this->getLogger()->debug($successMsg);

            return Report::createSuccess($successMsg);
        } catch (Exception $e) {
            $msg = "DB migration failed.";
            $this->getLogger()->error($msg, [
                'message'   => $e->getMessage(),
                'trace'     => $e->getTrace(),
            ]);

            return Report::createError($msg, [
                'message'   => $e->getMessage(),
                'trace'     => $e->getTrace(),
            ]);
        }

    }
}
