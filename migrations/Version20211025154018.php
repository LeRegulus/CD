<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025154018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, trajet_id INT DEFAULT NULL, mail VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CD12A823 ON comment (trajet_id)');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, trajet_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955D12A823 ON reservation (trajet_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE TABLE trajet (id INT NOT NULL, user_id INT NOT NULL, origine VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, places INT NOT NULL, lieu_depart VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B5BA98CA76ED395 ON trajet (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, birthday TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, profil_photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CD12A823');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955D12A823');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE trajet DROP CONSTRAINT FK_2B5BA98CA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE trajet');
        $this->addSql('DROP TABLE "user"');
    }
}
