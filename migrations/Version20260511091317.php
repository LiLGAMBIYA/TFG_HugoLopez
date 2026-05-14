<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511091317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cita_pieza (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cantidad_usada INTEGER DEFAULT 1 NOT NULL, cita_id INTEGER NOT NULL, pieza_id INTEGER NOT NULL, CONSTRAINT FK_CF1C13DC1E011DDF FOREIGN KEY (cita_id) REFERENCES cita (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CF1C13DC269DAD0C FOREIGN KEY (pieza_id) REFERENCES pieza (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CF1C13DC1E011DDF ON cita_pieza (cita_id)');
        $this->addSql('CREATE INDEX IDX_CF1C13DC269DAD0C ON cita_pieza (pieza_id)');
        $this->addSql('CREATE TABLE factura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero_factura VARCHAR(20) NOT NULL, fecha_emision DATETIME NOT NULL, base_imponible DOUBLE PRECISION NOT NULL, iva DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, cita_id INTEGER DEFAULT NULL, CONSTRAINT FK_F9EBA0091E011DDF FOREIGN KEY (cita_id) REFERENCES cita (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9EBA0093FBFBEA8 ON factura (numero_factura)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9EBA0091E011DDF ON factura (cita_id)');
        $this->addSql('CREATE TABLE pieza (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, referencia VARCHAR(50) NOT NULL, nombre VARCHAR(100) NOT NULL, precio_unidad DOUBLE PRECISION NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8A76622C01213D8 ON pieza (referencia)');
        $this->addSql('CREATE TABLE vehiculo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, matricula VARCHAR(20) NOT NULL, marca VARCHAR(50) NOT NULL, modelo VARCHAR(50) NOT NULL, vin VARCHAR(50) DEFAULT NULL, propietario_id INTEGER NOT NULL, CONSTRAINT FK_C9FA160353C8D32C FOREIGN KEY (propietario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C9FA160315DF1885 ON vehiculo (matricula)');
        $this->addSql('CREATE INDEX IDX_C9FA160353C8D32C ON vehiculo (propietario_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cita AS SELECT id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id FROM cita');
        $this->addSql('DROP TABLE cita');
        $this->addSql('CREATE TABLE cita (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descripcion_averia CLOB NOT NULL, fecha_deseada DATETIME NOT NULL, estado VARCHAR(50) NOT NULL, fecha_creacion DATETIME NOT NULL, servicio_id INTEGER NOT NULL, vehiculo_id INTEGER NOT NULL, cliente_id INTEGER NOT NULL, operario_id INTEGER DEFAULT NULL, CONSTRAINT FK_3E379A6271CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E379A6225F7D575 FOREIGN KEY (vehiculo_id) REFERENCES vehiculo (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E379A62DE734E51 FOREIGN KEY (cliente_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E379A62A32F015C FOREIGN KEY (operario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cita (id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id) SELECT id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id FROM __temp__cita');
        $this->addSql('DROP TABLE __temp__cita');
        $this->addSql('CREATE INDEX IDX_3E379A6271CAA3E7 ON cita (servicio_id)');
        $this->addSql('CREATE INDEX IDX_3E379A6225F7D575 ON cita (vehiculo_id)');
        $this->addSql('CREATE INDEX IDX_3E379A62DE734E51 ON cita (cliente_id)');
        $this->addSql('CREATE INDEX IDX_3E379A62A32F015C ON cita (operario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cita_pieza');
        $this->addSql('DROP TABLE factura');
        $this->addSql('DROP TABLE pieza');
        $this->addSql('DROP TABLE vehiculo');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cita AS SELECT id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id FROM cita');
        $this->addSql('DROP TABLE cita');
        $this->addSql('CREATE TABLE cita (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descripcion_averia CLOB NOT NULL, fecha_deseada DATETIME NOT NULL, estado VARCHAR(20) NOT NULL, fecha_creacion DATETIME NOT NULL, servicio_id INTEGER NOT NULL, cliente_nombre VARCHAR(255) NOT NULL, telefono VARCHAR(20) NOT NULL, matricula VARCHAR(20) NOT NULL, modelo_coche VARCHAR(255) NOT NULL, CONSTRAINT FK_3E379A6271CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cita (id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id) SELECT id, descripcion_averia, fecha_deseada, estado, fecha_creacion, servicio_id FROM __temp__cita');
        $this->addSql('DROP TABLE __temp__cita');
        $this->addSql('CREATE INDEX IDX_3E379A6271CAA3E7 ON cita (servicio_id)');
    }
}
