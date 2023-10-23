<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use B2bAcl\OfferExample\OfferEntity;
use function json_encode;

class OrderEntityTest extends \PHPUnit\Framework\TestCase
{
    public function test_model_creation_from_database(): void
    {
        /** @var OfferEntity $entity */
        $entity = new OfferEntity();

        $data = [
            'id' => '123',
            's_user_id' => '250',
            'name' => 'foo',
            'description' => 'bar',
        ];

        $entity->fromDatabaseArray($data);

        self::assertInstanceOf(OfferEntity::class, $entity);
        self::assertEquals('123', $entity->id);
        self::assertEquals('foo', $entity->name);
        self::assertEquals('bar', $entity->description);
        self::assertEquals('250', $entity->userId);

        $toDatabaseArray = $entity->toDatabaseArray();

        self::assertEquals($data, $toDatabaseArray);

        self::assertFalse($entity->isNew());
    }

    public function test_set_data(): void
    {
        /** @var OfferEntity $entity */
        $entity = new OfferEntity();

        $data = [
            's_user_id' => '250',
            'name' => 'foo',
            'description' => 'bar',
            'skippingParameter' => true,
        ];

        $entity->setData($data);

        self::assertNotEquals($data, $entity->toDatabaseArray());
        self::assertJson(json_encode($entity));
    }
}
