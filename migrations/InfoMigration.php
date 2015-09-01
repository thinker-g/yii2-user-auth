<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\migrations;

use thinker_g\Helpers\migrations\CreationMigration;

/**
 * Migration for creating table of extra user information.
 *
 * @author Thinker_g
 */
class InfoMigration extends CreationMigration
{
    public $tables = [
        '{{%user_info}}' => [
            self::K_COLS => [
                'user_id' => 'INT PRIMARY KEY',
                'is_male' => 'BOOLEAN NULL',
                'dob' => 'DATE NULL DEFAULT NULL',
                'board_type' => 'TEXT NULL',
                'ski_age' => 'VARCHAR(32) NULL',
            ],
            self::K_FK => [
                'fk_user_info_user' => ['user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE']
            ]
        ] // user_info
    ];
}
