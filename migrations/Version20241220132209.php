<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220132209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication_tag (publication_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_20D75B4C38B217A7 (publication_id), INDEX IDX_20D75B4CBAD26311 (tag_id), PRIMARY KEY(publication_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication_tag ADD CONSTRAINT FK_20D75B4C38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_tag ADD CONSTRAINT FK_20D75B4CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_tag DROP FOREIGN KEY FK_20D75B4C38B217A7');
        $this->addSql('ALTER TABLE publication_tag DROP FOREIGN KEY FK_20D75B4CBAD26311');
        $this->addSql('DROP TABLE publication_tag');
    }
}
