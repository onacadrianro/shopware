<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

use Shopware\B2B\Common\CrudEntity;
use Shopware\B2B\Common\IdValue;
use Shopware\B2B\Common\NullIdValue;
use function get_object_vars;
use function property_exists;

class OfferEntity implements CrudEntity
{
    public IdValue $id;

    public IdValue $userId;

    public string $name;

    public string $description;

    public function __construct()
    {
        $this->id = IdValue::null();
        $this->userId = IdValue::null();
    }

    /**
     * {@inheritdoc}
     */
    public function isNew(): bool
    {
        return $this->id instanceof NullIdValue;
    }

    /**
     * {@inheritdoc}
     */
    public function toDatabaseArray(): array
    {
        return [
            'id' => $this->id->getStorageValue(),
            'user_id' => $this->userId->getStorageValue(),
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabaseArray(array $data): CrudEntity
    {
        $this->id = IdValue::create($data['id']);
        $this->userId = IdValue::create($data['user_id']);
        $this->name = $data['name'];
        $this->description = $data['description'];

        return $this;
    }

    public function setData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
