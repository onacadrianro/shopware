<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use B2bAcl\OfferExample\AclTableContactContextResolver;
use B2bAcl\OfferExample\OfferAclTable;
use ReflectionMethod;

class OfferAclTableTest extends \PHPUnit\Framework\TestCase
{
    public function test_creation_of_acl_table(): void
    {
        $aclTable = new OfferAclTable();
        self::assertInstanceOf(OfferAclTable::class, $aclTable);

        $method = new ReflectionMethod('\\B2bAcl\\Offer\\OfferAclTable', 'getContextResolvers');
        $method->setAccessible(true);
        $resolvers = $method->invoke(new OfferAclTable());

        self::assertInternalType('array', $resolvers);
        self::assertContainsOnlyInstancesOf(AclTableContactContextResolver::class, $resolvers);
    }
}
