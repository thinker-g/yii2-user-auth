<?php
namespace thinker_g\UserAuth\migrations;

use yii\db\Migration;
use Yii;

/**
 * Migration for running all migrations for FULL feature support of the module.
 * If only simple authentication is needed, only UserMigration is enough.
 * If need extra user account information, ExtAccountMigration is needed after UserMigration.
 * For extra user info (age or names, etc.), InfoMigration is needed after UserMigration. 
 *
 * @author Thinker_g
 */
class FullInitMigration extends Migration
{
    public $allMigrations = [
        'thinker_g\UserAuth\migrations\UserMigration',
        'thinker_g\UserAuth\migrations\InfoMigration',
        'thinker_g\UserAuth\migrations\ExtAccountMigration',
    ];

    /**
     * @inheritdoc
     * @see \yii\db\Migration::up()
     */
    public function up()
    {
        foreach ($this->allMigrations as $migrationClass) {
            $migration = Yii::createObject($migrationClass);
            $migration->up();
        }
    }

    /**
     * @inheritdoc
     * @see \yii\db\Migration::down()
     */
    public function down()
    {
        krsort($this->allMigrations);
        foreach ($this->allMigrations as $migrationClass) {
            $migration = Yii::createObject($migrationClass);
            $migration->down();
        }
    }
}
