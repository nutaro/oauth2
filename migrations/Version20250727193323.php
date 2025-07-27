<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727193323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE token(id SERIAL, token VARCHAR(255) NOT NULL, app_id VARCHAR(255) NOT NULL, user_id INTEGER NOT NULL, created_at DATE NOT NULL, valid_until DATE NOT NULL, PRIMARY KEY(id))");
        $this->addSql("ALTER TABLE token ADD CONSTRAINT app_id_fk FOREIGN KEY (app_id) REFERENCES app (client_id) ON DELETE CASCADE ");
        $this->addSql("ALTER TABLE token ADD CONSTRAINT user_id_fk FOREIGN KEY (user_id) REFERENCES users (id)");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE token');
    }
}
