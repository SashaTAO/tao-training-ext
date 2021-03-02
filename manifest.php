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
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA;
 *               
 * 
 */

use oat\tao\model\accessControl\func\AccessRule;
use oat\taoTrainingExt\models\ontology\TrainingManagerRole;
use oat\taoTrainingExt\scripts\db\CreateTrainingReportTable;

/**
 * Generated using taoDevTools 6.10.0
 */
return [
    'name' => 'taoTrainingExt',
    'label' => 'Training extension',
    'description' => 'Training extension',
    'license' => 'GPL-2.0',
    'version' => '0.3.0',
    'author' => 'Open Assessment Technologies SA',
    'requires' => [
        'generis' => '>=13.14.1',
        'tao' => '>=46.14.4'
    ],
    'managementRole' => TrainingManagerRole::ROLE_URI,
    'acl' => [
        [
            AccessRule::GRANT, TrainingManagerRole::ROLE_URI, ['ext'=>'taoTrainingExt']
        ],
    ],
    'routes' => [
        '/taoTrainingExt' => 'oat\\taoTrainingExt\\controller',
    ],
    'install' => [
        'rdf' => [
            __DIR__ . '/install/ontology/training.rdf',
            __DIR__ . '/install/ontology/trainingManagerRole.rdf'
        ],
        'php' => [
            CreateTrainingReportTable::class,
        ]
    ],
    'uninstall' => [],
    'constants' => [
        # views directory
        "DIR_VIEWS" => __DIR__ . DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR,

        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL.'taoCustomExtension/',
    ],
    'extra' => [
        'structures' => __DIR__ . DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'structures.xml',
    ]
];