<?php declare(strict_types=1);

namespace B2bAcl\Tests\Integration;

use B2bAcl\OfferExample\OfferRepository;
use B2bAcl\OfferExample\OfferSearchStruct;
use PHPUnit\Framework\TestCase;
use Shopware\B2B\Common\Testing\KernelTestCaseTrait;
use Shopware\B2BTest\Debtor\MockIdentity;

class OfferRepositoryTest extends TestCase
{
    use KernelTestCaseTrait;

    private function getRepository(): OfferRepository
    {
        return self::getKernel()->getContainer()->get('b2b_offer.repository');
    }

    public function test_repository_instance(): void
    {
        self::assertInstanceOf(OfferRepository::class, $this->getRepository());
    }

    public function test_repository(): void
    {
        $identity = new MockIdentity();
        $ownershipContext = $identity->getOwnershipContext();
        $ownershipContext->shopOwnerUserId = 250;
        $ownershipContext->identityId = 11;
        $ownershipContext->identityClassName = \Shopware\B2B\Contact\Framework\ContactIdentity::class;

        $searchStruct = new OfferSearchStruct();
        $result = $this->getRepository()->fetchList($ownershipContext, $searchStruct);

        self::assertCount(0, $result);

        $result = $this->getRepository()->fetchTotalCount($ownershipContext, $searchStruct);

        self::assertEquals(0, $result);
    }
}
