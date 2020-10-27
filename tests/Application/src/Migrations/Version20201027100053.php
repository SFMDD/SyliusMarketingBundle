<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201027100053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_channel ADD url_privacy VARCHAR(255) DEFAULT NULL, ADD contact_phone VARCHAR(255) DEFAULT NULL, ADD contact_opening_time VARCHAR(255) DEFAULT NULL, ADD contact_geo_latitude_longitude VARCHAR(255) DEFAULT NULL, ADD contact_geo_opening_hours VARCHAR(255) DEFAULT NULL, ADD facebook_pixel VARCHAR(255) DEFAULT NULL, ADD google_analytics VARCHAR(255) DEFAULT NULL, ADD google_adwords VARCHAR(255) DEFAULT NULL, ADD google_id VARCHAR(255) DEFAULT NULL, ADD google_type_merchant VARCHAR(255) DEFAULT NULL, ADD google_event_purchase VARCHAR(255) DEFAULT NULL, ADD google_event_search VARCHAR(255) DEFAULT NULL, ADD google_event_product_show VARCHAR(255) DEFAULT NULL, ADD google_event_registration VARCHAR(255) DEFAULT NULL, ADD google_event_checkout_progress VARCHAR(255) DEFAULT NULL, ADD google_event_select_payment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD trustpilot_enabled TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_channel DROP url_privacy, DROP contact_phone, DROP contact_opening_time, DROP contact_geo_latitude_longitude, DROP contact_geo_opening_hours, DROP facebook_pixel, DROP google_analytics, DROP google_adwords, DROP google_id, DROP google_type_merchant, DROP google_event_purchase, DROP google_event_search, DROP google_event_product_show, DROP google_event_registration, DROP google_event_checkout_progress, DROP google_event_select_payment');
        $this->addSql('ALTER TABLE sylius_customer DROP trustpilot_enabled');
    }
}
