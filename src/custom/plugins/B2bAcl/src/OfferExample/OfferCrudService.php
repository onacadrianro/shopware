<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

use Shopware\B2B\Acl\Framework\AclRepository;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Common\IdValue;
use Shopware\B2B\Common\Service\AbstractCrudService;
use Shopware\B2B\Common\Service\CrudServiceRequest;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class OfferCrudService extends AbstractCrudService
{
    private OfferRepository $offerRepository;

    private OfferValidationService $offerValidationService;

    private AclRepository $aclRepository;

    public function __construct(
        OfferRepository $offerRepository,
        OfferValidationService $offerValidationService,
        AclRepository $aclRepository
    ) {
        $this->offerRepository = $offerRepository;
        $this->offerValidationService = $offerValidationService;
        $this->aclRepository = $aclRepository;
    }

    public function createNewRecordRequest(array $data): CrudServiceRequest
    {
        return new CrudServiceRequest(
            $data,
            [
                'name',
                'description',
            ]
        );
    }

    public function createExistingRecordRequest(array $data): CrudServiceRequest
    {
        return new CrudServiceRequest(
            $data,
            [
                'id',
                'name',
                'description',
            ]
        );
    }

    /**
     * @throws \Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     */
    public function create(CrudServiceRequest $request, OwnershipContext $ownershipContext): OfferEntity
    {
        $data = $request->getFilteredData();
        $data['userId'] = $ownershipContext->shopOwnerUserId;

        $offer = new OfferEntity();
        $offer->setData($data);

        $validation = $this->offerValidationService
            ->createInsertValidation($offer);

        $this->testValidation($offer, $validation);

        $offer = $this->offerRepository
            ->addOffer($offer);

        try {
            $this->aclRepository->allow(
                $ownershipContext,
                IdValue::create($offer->id)
            );
        } catch (AclUnsupportedContextException $e) {
            return $offer;
        }

        return $offer;
    }

    /**
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     * @throws \Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException
     */
    public function update(CrudServiceRequest $request, OwnershipContext $ownershipContext): OfferEntity
    {
        $data = $request->getFilteredData();
        $offer = new OfferEntity();
        $offer->setData($data);
        $offer->id = $offer->id;
        $offer->userId = $ownershipContext->shopOwnerUserId->getValue();

        $validation = $this->offerValidationService
            ->createUpdateValidation($offer);

        $this->testValidation($offer, $validation);

        $this->offerRepository
            ->updateOffer($offer);

        return $offer;
    }

    /**
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveUsedRecordException
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException
     */
    public function remove(CrudServiceRequest $request): OfferEntity
    {
        $data = $request->getFilteredData();

        $offer = $this->offerRepository->fetchOneById((int) $data['id']);

        $this->offerRepository
            ->removeOffer($offer);

        return $offer;
    }
}
