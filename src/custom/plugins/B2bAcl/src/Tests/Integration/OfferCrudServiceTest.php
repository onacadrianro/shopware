<?php declare(strict_types=1);

namespace B2bAcl\Tests\Integration;

use B2bAcl\OfferExample\OfferCrudService;
use B2bAcl\OfferExample\OfferEntity;
use PHPUnit\Framework\TestCase;
use Shopware\B2B\Common\Service\CrudServiceRequest;
use Shopware\B2B\Common\Testing\CommonTestFixtures;
use Shopware\B2B\Common\Testing\KernelTestCaseTrait;

class OfferCrudServiceTest extends TestCase
{
    use KernelTestCaseTrait;

    private function getCrudService(): OfferCrudService
    {
        return $this->getContainer()->get('b2b_offer.crud_service');
    }

    public function test_crud_service_instance(): void
    {
        self::assertInstanceOf(OfferCrudService::class, $this->getCrudService());
    }

    public function test_crud_service(): void
    {
        $this->importFixture(new CommonTestFixtures());

        $data = ['name' => 'name', 'description' => 'description'];
        $createRequest = $this->getCrudService()->createNewRecordRequest($data);

        self::assertInstanceOf(CrudServiceRequest::class, $createRequest);

        $ownershipContext = $this->createContactIdentity()
            ->getOwnershipContext();

        $offerEntity = $this->getCrudService()
            ->create($createRequest, $ownershipContext);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);

        $offerEntity->description = 'updated Description';

        $existingRecordRequest = $this->getCrudService()->createExistingRecordRequest($offerEntity->toArray());

        $offerEntity = $this->getCrudService()->update($existingRecordRequest, $ownershipContext);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);
        self::assertEquals('updated Description', $offerEntity->description);

        $existingRecordRequest = $this->getCrudService()->createExistingRecordRequest($offerEntity->toArray());

        $offerEntity = $this->getCrudService()->remove($existingRecordRequest);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);
        self::assertNull($offerEntity->id);
    }
}
