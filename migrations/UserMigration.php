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
 * Migration for creating table of basic user credentials.
 *
 * @author Thinker_g
 */
class UserMigration extends CreationMigration
{
    public $tables = [
        '{{%user}}' => [
            self::K_COLS => [
                'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
                'username' => 'VARCHAR(255) NOT NULL',
                'primary_email' => 'VARCHAR(255) NULL',
                'password_hash' => 'VARCHAR(255) NULL',
                'status' => 'TINYINT(3) UNSIGNED NULL',
                'auth_key' => 'VARCHAR(64) NULL',
                'password_reset_token' => 'VARCHAR(255) NULL',
                'created_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP NULL',
                'last_login_at' => 'TIMESTAMP NULL'
            ],
            self::K_IDXS => [
                'idx_email' => [
                    self::K_COLS => ['primary_email', 'status']
                ]
            ]
        ], // user
    ];


}
