<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

use Shopware\B2B\Acl\Framework\AclContextResolverMain;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Common\IdValue;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;
use function is_a;

class AclTableContactContextResolver extends AclContextResolverMain
{
    /**
     * @param object $context
     * @throws AclUnsupportedContextException
     */
    public function extractId($context): IdValue
    {
        if ($context instanceof OwnershipContext && is_a($context->identityClassName, ContactIdentity::class, true)) {
            return $context->identityId;
        }

        if ($context instanceof ContactEntity && $context->id) {
            return $context->id;
        }

        throw new AclUnsupportedContextException();
    }
}
