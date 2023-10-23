<?php declare(strict_types=1);

namespace ShopwareBlogTest;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Kernel;

class ShopwareBlogTest extends Plugin
{

    /**
     * Install plugin
     *
     * @param InstallContext $installContext
     * @return void
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
    }

    /**
     * Uninstall plugin
     *
     * @param UninstallContext $context
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function uninstall(UninstallContext $context): void
    {
        $connection = Kernel::getConnection();
        $connection->executeStatement('DROP TABLE IF EXISTS `sw_blog_posts`');

        parent::uninstall($context);
    }

}
