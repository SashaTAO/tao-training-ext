<?php

declare(strict_types=1);

namespace oat\taoTrainingExt\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\tao\model\accessControl\func\AccessRule;
use oat\tao\model\accessControl\func\AclProxy;
use oat\tao\scripts\tools\migrations\AbstractMigration;
use oat\tao\scripts\update\OntologyUpdater;
use oat\taoTrainingExt\models\ontology\TrainingManagerRole;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202102241221202816_taoTrainingExt extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Create a new Training resource and role to grant access to Training related actions.';
    }

    public function up(Schema $schema): void
    {
        OntologyUpdater::syncModels();
        AclProxy::applyRule(
            new AccessRule(
                AccessRule::GRANT,
                TrainingManagerRole::ROLE_URI,
                ['ext' => 'taoTrainingExt']
            )
        );

    }

    public function down(Schema $schema): void
    {
        AclProxy::revokeRule(
            new AccessRule(
                AccessRule::GRANT,
                TrainingManagerRole::ROLE_URI,
                ['ext' => 'taoTrainingExt']
            )
        );
        OntologyUpdater::syncModels();
    }
}
