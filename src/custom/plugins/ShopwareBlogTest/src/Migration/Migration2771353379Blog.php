<?php declare(strict_types=1);

namespace ShopwareBlogTest\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration2771353379Blog extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 2771353379;
    }

    public function update(Connection $connection): void
    {

        $connection->executeStatement(
            '
            CREATE TABLE IF NOT EXISTS `sw_blog_posts` (
            `id` BINARY(16) NOT NULL,
            `lang_id` BINARY(16) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `short_description` VARCHAR(255) NULL,
            `description` VARCHAR(255) NULL,
            `content` MEDIUMTEXT COLLATE utf8mb4_unicode_ci NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`, `lang_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        '
        );

    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
