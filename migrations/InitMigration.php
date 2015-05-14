<?php
namespace thinker_g\UserAuth\migrations;

use thinker_g\Helpers\migrations\CreationMigration;

class InitMigration extends CreationMigration
{
    public $tables = [
        '{{%user}}' => [
            'COLS' => [
                'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
                'username' => 'VARCHAR(255) NOT NULL',
                'primary_email' => 'VARCHAR(255) NULL',
                'password_hash' => 'VARCHAR(255) NULL',
                'status' => 'SMALLINT(6) NULL',
                'auth_key' => 'VARCHAR(64) NULL',
                'password_reset_token' => 'VARCHAR(255) NULL',
                'created_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'last_login_at' => 'TIMESTAMP NULL'
            ],
            'IDXS' => [
                'idx_email' => [
                    'COLS' => ['primary_email', 'status']
                ]
            ]
        ], // user

        '{{%user_ext_account}}' => [
            'COLS' => [
                'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
                'user_id' => 'INT',
                'from_source' => 'VARCHAR(64) NOT NULL',
                'access_token' => 'VARCHAR(255) NOT NULL',
                'ext_user_id' => 'INT NULL DEFAULT NULL',
                'email' => 'VARCHAR(255) NULL',
                'created_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP NULL',
            ],
            'FK' => [
                'fk_ext_account_user' => ['user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE']
            ],
            'IDXS' => [
                'idx_ext_email' => [
                    'COLS' => ['email', 'from_source']
                ]
            ]
        ], // user_account

        '{{%user_info}}' => [
            'COLS' => [
                'user_id' => 'INT PRIMARY KEY',
                'is_male' => 'BOOLEAN NULL',
                'dob' => 'DATE NULL DEFAULT NULL',
                'board_type' => 'TEXT NULL',
                'ski_age' => 'VARCHAR(32) NULL',
            ],
            'FK' => [
                'fk_user_info_user' => ['user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE']
            ]

        ] // user_info
    ];


}
