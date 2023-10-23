<?php declare(strict_types=1);

namespace B2bAcl;

use Doctrine\DBAL\Connection;
use Shopware\B2B\Acl\Framework\AclDdlService;
use Shopware\B2B\AclRoute\Framework\AclRoutingUpdateService;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;

class B2bAcl extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        $connection = $this->container->get(Connection::class);
        $connection->executeStatement(
            'CREATE TABLE IF NOT EXISTS `b2b_offer_example` (
               `id` INT(11) NOT NULL AUTO_INCREMENT,
               `user_id` BINARY(16) NULL DEFAULT NULL,
               `name` VARCHAR(255) NOT NULL COLLATE \'utf8_unicode_ci\',
               `description` TEXT NULL COLLATE \'utf8_unicode_ci\',

               PRIMARY KEY (`id`),

               CONSTRAINT b2b_offer_example_s_user_id_FK FOREIGN KEY (`user_id`)
                 REFERENCES `customer` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            )
              COLLATE = utf8_unicode_ci;'
        );

        $connection->createTable(new OfferExample\OfferAclTable());

        parent::install($installContext);
    }
}
