<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use PDO;
use Shopware\B2B\Acl\Framework\AclRepository;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Common\Controller\GridRepository;
use Shopware\B2B\Common\IdValue;
use Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException;
use Shopware\B2B\Common\Repository\DbalHelper;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class OfferRepository implements GridRepository
{
    public const TABLE_NAME = 'b2b_offer_example';

    public const TABLE_ALIAS = 'offer_example';

    private Connection $connection;

    private DbalHelper $dbalHelper;

    private AclRepository $aclRepository;

    public function __construct(
        Connection $connection,
        DbalHelper $dbalHelper,
        AclRepository $aclRepository = null
    ) {
        $this->connection = $connection;
        $this->dbalHelper = $dbalHelper;
        $this->aclRepository = $aclRepository;
    }

    /**
     * @throws InvalidArgumentException
     * @return OfferEntity[]
     */
    public function fetchList(OwnershipContext $context, OfferSearchStruct $searchStruct): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(self::TABLE_ALIAS . '.*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.user_id = :owner')
            ->setParameter('owner', $context->shopOwnerUserId->getStorageValue());

        $this->applyAcl($context, $query);

        if (!$searchStruct->orderBy) {
            $searchStruct->orderBy = self::TABLE_ALIAS . '.id';
            $searchStruct->orderDirection = 'DESC';
        }

        $this->dbalHelper->applySearchStruct($searchStruct, $query);

        $statement = $query->execute();

        $offersData = $statement
            ->fetchAll(PDO::FETCH_ASSOC);

        $offers = [];
        foreach ($offersData as $offerData) {
            $offers[] = (new OfferEntity())->fromDatabaseArray($offerData);
        }

        return $offers;
    }

    public function fetchTotalCount(OwnershipContext $context, OfferSearchStruct $searchStruct): int
    {
        $query = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.user_id = :owner')
            ->setParameter('owner', $context->shopOwnerUserId->getStorageValue());

        $this->applyAcl($context, $query);
        $this->dbalHelper->applyFilters($searchStruct, $query);

        $statement = $query->execute();

        return (int) $statement->fetchColumn(0);
    }

    public function fetchOneById(int $id): OfferEntity
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.id = :id')
            ->setParameter('id', $id)
            ->execute();

        $offerData = $statement->fetch(PDO::FETCH_ASSOC);

        return (new OfferEntity())->fromDatabaseArray($offerData);
    }

    /**
     * @throws \Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException
     */
    public function addOffer(OfferEntity $offerEntity): OfferEntity
    {
        if (!$offerEntity->isNew()) {
            throw new CanNotInsertExistingRecordException('The Offer provided already exists');
        }

        $this->connection->insert(
            self::TABLE_NAME,
            $offerEntity->toDatabaseArray()
        );

        $offerEntity->id = IdValue::create($this->connection->lastInsertId());

        return $offerEntity;
    }

    /**
     * @throws \Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException
     */
    public function updateOffer(OfferEntity $offerEntity): OfferEntity
    {
        if ($offerEntity->isNew()) {
            throw new CanNotUpdateExistingRecordException('The Offer provided does not exist');
        }

        $this->connection->update(
            self::TABLE_NAME,
            $offerEntity->toDatabaseArray(),
            ['id' => $offerEntity->id]
        );

        return $offerEntity;
    }

    /**
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException
     */
    public function removeOffer(OfferEntity $offerEntity): OfferEntity
    {
        if ($offerEntity->isNew()) {
            throw new CanNotRemoveExistingRecordException('The Offer provided does not exist');
        }

        $this->connection->delete(
            self::TABLE_NAME,
            ['id' => $offerEntity->id]
        );

        return $offerEntity;
    }

    /**
     * @return string query alias for filter construction
     */
    public function getMainTableAlias(): string
    {
        return self::TABLE_ALIAS;
    }

    /**
     * @return string[]
     */
    public function getFullTextSearchFields(): array
    {
        return [
            'name',
            'description',
        ];
    }

    private function applyAcl(OwnershipContext $context, QueryBuilder $query): void
    {
        try {
            $aclQuery = $this->aclRepository->getUnionizedSqlQuery($context);

            $query->innerJoin(
                self::TABLE_ALIAS,
                '(' . $aclQuery->sql . ')',
                'acl_query',
                self::TABLE_ALIAS . '.id = acl_query.referenced_entity_id'
            );

            foreach ($aclQuery->params as $name => $value) {
                $query->setParameter($name, $value);
            }
        } catch (AclUnsupportedContextException $e) {
            // nth
        }
    }

    public function getAdditionalSearchResourceAndFields(): array
    {
        return [];
    }

    public function getAllowedSortingFields(): array
    {
        return [
            'id',
            'email',
            'created_at',
            'expired_at',
            'discount_amount',
        ];
    }
}
