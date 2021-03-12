<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310182712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE respuesta (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, mensajes_id INT DEFAULT NULL, contenido VARCHAR(255) NOT NULL, imagen VARCHAR(255) NOT NULL, INDEX IDX_6C6EC5EEDB38439E (usuario_id), INDEX IDX_6C6EC5EE4481E9A2 (mensajes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EEDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EE4481E9A2 FOREIGN KEY (mensajes_id) REFERENCES mensajes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE respuesta');
    }
}
