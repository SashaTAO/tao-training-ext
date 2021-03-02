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

use common_persistence_SqlPersistence;
use oat\generis\persistence\PersistenceManager;
use oat\oatbox\service\ConfigurableService;

class TrainingReportService extends ConfigurableService
{
    public const SERVICE_ID = 'taoTrainingExt/trainingReportService';

    public const OPTION_PERSISTENCE = 'persistence';

    public const TABLE_NAME = 'training_reports';

    /**
     * @var common_persistence_SqlPersistence
     */
    private $persistence;

    /**
     * Get Persistence.
     *
     * Retrieve the appropriate persistence depending on the self::OPTION_PERSISTENCE
     * option. If no value is set for option self::OPTION_PERSISTENCE, the 'default'
     * persistence will be retrieve to access the database.
     *
     * @return common_persistence_SqlPersistence
     */
    public function getPersistence(): common_persistence_SqlPersistence
    {
        if ($this->persistence === null) {
            $persistenceId = $this->hasOption(self::OPTION_PERSISTENCE)
                ? $this->getOption(self::OPTION_PERSISTENCE)
                : 'default';
            $this->persistence = $this->getServiceLocator()
                ->get(PersistenceManager::SERVICE_ID)
                ->getPersistenceById($persistenceId);
        }

        return $this->persistence;
    }
}
