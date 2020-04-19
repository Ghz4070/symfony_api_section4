<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410115651 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE annee annee INT DEFAULT NULL, CHANGE langue langue VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C83256915B');
        $this->addSql('DROP INDEX IDX_BDAFD8C83256915B ON author');
        $this->addSql('ALTER TABLE author CHANGE relation_id nationalite_id INT NOT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C81B063272 FOREIGN KEY (nationalite_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_BDAFD8C81B063272 ON author (nationalite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C81B063272');
        $this->addSql('DROP INDEX IDX_BDAFD8C81B063272 ON author');
        $this->addSql('ALTER TABLE author CHANGE nationalite_id relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C83256915B FOREIGN KEY (relation_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_BDAFD8C83256915B ON author (relation_id)');
        $this->addSql('ALTER TABLE book CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE annee annee INT DEFAULT NULL, CHANGE langue langue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
