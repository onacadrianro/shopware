<?php declare(strict_types=1);

namespace B2bAcl\Controllers\Frontend;

use B2bAcl\OfferExample\OfferCrudService;
use B2bAcl\OfferExample\OfferRepository;
use B2bAcl\OfferExample\OfferSearchStruct;
use Shopware\B2B\Common\Controller\B2bControllerRedirectException;
use Shopware\B2B\Common\Controller\GridHelper;
use Shopware\B2B\Common\MvcExtension\Request;
use Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException;
use Shopware\B2B\Common\Validator\ValidationException;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;

class B2bOfferController
{
    private OfferRepository $offerRepository;

    private AuthenticationService $authenticationService;

    private OfferCrudService $offerCrudService;

    private GridHelper $gridHelper;

    public function __construct(
        OfferRepository $offerRepository,
        AuthenticationService $authenticationService,
        OfferCrudService $offerCrudService,
        GridHelper $gridHelper
    ) {
        $this->offerRepository = $offerRepository;
        $this->authenticationService = $authenticationService;
        $this->offerCrudService = $offerCrudService;
        $this->gridHelper = $gridHelper;
    }

    public function indexAction(): void
    {
        //nth
    }

    public function gridAction(Request $request): array
    {
        $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();
        $searchStruct = new OfferSearchStruct();

        $this->gridHelper
            ->extractSearchDataInStoreFront($request, $searchStruct);

        $offers = $this->offerRepository
            ->fetchList($ownershipContext, $searchStruct);

        $totalCount = $this->offerRepository
            ->fetchTotalCount($ownershipContext, $searchStruct);

        $maxPage = $this->gridHelper
            ->getMaxPage($totalCount);

        $currentPage = (int) $request
            ->getParam('page', 1);

        $gridState = $this->gridHelper
            ->getGridState($request, $searchStruct, $offers, $maxPage, $currentPage);

        return [
            'gridState' => $gridState,
        ];
    }

    public function newAction(): array
    {
        return $this->gridHelper->getValidationResponse('offer');
    }

    public function createAction(Request $request): void
    {
        $request->checkPost();
        $post = $request->getPost();

        $serviceRequest = $this->offerCrudService->createNewRecordRequest($post);

        $identity = $this->authenticationService->getIdentity();

        try {
            $offer = $this->offerCrudService
                ->create($serviceRequest, $identity->getOwnershipContext());
        } catch (ValidationException $e) {
            $this->gridHelper->pushValidationException($e);
            throw new B2bControllerRedirectException(
                'new',
                'b2bofferexample'
            );
        }

        throw new B2bControllerRedirectException(
            'detail',
            'b2bofferexample',
            ['id' => $offer->id]
        );
    }

    public function detailAction(Request $request): array
    {
        $id = (int) $request->requireParam('id');

        return [
            'offer' => $this->offerRepository->fetchOneById($id),
            'id' => (int) $request->requireParam('id'),
        ];
    }

    public function editAction(Request $request): array
    {
        $id = (int) $request->requireParam('id');

        if (!$this->gridHelper->getValidationResponse('offer')) {
            return ['offer' => $this->offerRepository->fetchOneById($id)];
        }

        return [];
    }

    public function updateAction(Request $request): void
    {
        $request->checkPost();

        $post = $request->getPost();

        $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();
        $serviceRequest = $this->offerCrudService->createExistingRecordRequest($post);

        try {
            $this->offerCrudService
                ->update($serviceRequest, $ownershipContext);
        } catch (ValidationException $e) {
            $this->gridHelper
                ->pushValidationException($e);
        }

        throw new B2bControllerRedirectException(
            'edit',
            'b2bofferexample',
            ['id' => $serviceRequest->requireParam('id')]
        );
    }

    public function removeAction(Request $request): void
    {
        $request->checkPost();

        $serviceRequest = $this->offerCrudService
            ->createExistingRecordRequest($request->getPost());

        try {
            $this->offerCrudService->remove($serviceRequest);
        } catch (CanNotRemoveExistingRecordException $e) {
            // nth
        }

        throw new B2bControllerRedirectException('grid', 'b2bofferexample');
    }
}
