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
 * Migration for creating table of external user accounts.
 *
 * @author Thinker_g
 */
class ExtAccountMigration extends CreationMigration
{
    //@todo ext_user_id should be changed to string.
    public $tables = [
        '{{%user_ext_account}}' => [
            self::K_COLS => [
                'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
                'user_id' => 'INT',
                'from_source' => 'VARCHAR(64) NOT NULL',
                'access_token' => 'VARCHAR(255) NOT NULL',
                'ext_user_id' => 'INT NULL DEFAULT NULL',
                'email' => 'VARCHAR(255) NULL',
                'created_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP NULL',
            ],
            self::K_FK => [
                'fk_ext_account_user' => ['user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE']
            ],
            self::K_IDXS => [
                'idx_ext_email' => [
                    self::K_COLS => ['email', 'from_source']
                ]
            ]
        ], // user_account
    ];

}
