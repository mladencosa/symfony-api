<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210614171729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating tables countries, user_details, users, transactions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE countries (
                    id INT AUTO_INCREMENT NOT NULL,
                    name VARCHAR(63) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT 'English country name.',
                    iso2 CHAR(2) CHARACTER SET ascii NOT NULL COLLATE `ascii_bin` COMMENT 'ISO 3166-2 two letter upper case country code.',
                    iso3 CHAR(3) CHARACTER SET ascii DEFAULT NULL COLLATE `ascii_bin` COMMENT 'ISO 3166-3 three letter upper case country code.',
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
SQL);

        $this->addSql(<<<SQL
            CREATE TABLE user_details (
                    id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL,
                    citizenship_country_id INT NOT NULL,
                    first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    phone_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
SQL);

        $this->addSql(<<<SQL
            CREATE TABLE users (
                    id INT AUTO_INCREMENT NOT NULL,
                    email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    active TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL,
                    updated_at DATETIME DEFAULT NULL, 
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
SQL);

        $this->addSql(<<<SQL
            CREATE TABLE transactions (
                    id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                    code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    amount VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    user_id INT UNSIGNED NOT NULL,
                    created_at DATETIME DEFAULT NULL,
                    updated_at DATETIME DEFAULT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
SQL);

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_details');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
    }
}
