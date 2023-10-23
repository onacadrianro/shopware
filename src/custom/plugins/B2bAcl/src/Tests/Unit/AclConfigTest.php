<?php declare(strict_types=1);

namespace B2bAcl\Tests\Unit;

use B2bAcl\OfferExample\AclConfig;

class AclConfigTest extends \PHPUnit\Framework\TestCase
{
    public function test_config_returns_array(): void
    {
        $aclConfig = AclConfig::getAclConfigArray();
        self::assertInternalType('array', $aclConfig);
    }
}
