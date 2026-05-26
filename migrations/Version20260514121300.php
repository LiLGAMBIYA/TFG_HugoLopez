<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260514121300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add service description field for the public catalogue.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE servicio ADD descripcion TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE servicio DROP descripcion');
    }
}
