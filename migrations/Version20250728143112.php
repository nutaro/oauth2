<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728143112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refresh_token (id SERIAL NOT NULL, user_id INT NOT NULL, app_id VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C74F2195A76ED395 ON refresh_token (user_id)');
        $this->addSql('CREATE INDEX IDX_C74F21957987212D ON refresh_token (app_id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F21957987212D FOREIGN KEY (app_id) REFERENCES app (client_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX unique_name RENAME TO UNIQ_C96E70CF5E237E06');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT app_id_fk');
        $this->addSql('ALTER TABLE token ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE token ALTER valid_until TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B7987212D FOREIGN KEY (app_id) REFERENCES app (client_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE refresh_token DROP CONSTRAINT FK_C74F2195A76ED395');
        $this->addSql('ALTER TABLE refresh_token DROP CONSTRAINT FK_C74F21957987212D');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13B7987212D');
        $this->addSql('ALTER TABLE token ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE token ALTER valid_until TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT app_id_fk FOREIGN KEY (app_id) REFERENCES app (client_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX uniq_c96e70cf5e237e06 RENAME TO unique_name');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74');
    }
}
