<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128103727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_request (id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(255) NOT NULL, user_comment LONGTEXT NOT NULL, user_ip VARCHAR(15) NOT NULL, user_ym_uid VARCHAR(255) NOT NULL, user_geo VARCHAR(255) NOT NULL, user_width INT NOT NULL, user_fingerprint_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_visit (id INT AUTO_INCREMENT NOT NULL, user_ip VARCHAR(15) NOT NULL, site_page VARCHAR(255) NOT NULL, user_geo VARCHAR(255) NOT NULL, user_ym_uid VARCHAR(255) NOT NULL, user_width INT NOT NULL, user_fingerprint_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A1BC1261A6AB958B (user_ym_uid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_request');
        $this->addSql('DROP TABLE user_visit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
