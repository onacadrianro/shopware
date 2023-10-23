<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

use Shopware\B2B\Common\Validator\ValidationBuilder;
use Shopware\B2B\Common\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OfferValidationService
{
    private ValidationBuilder $validationBuilder;

    private ValidatorInterface $validator;

    public function __construct(
        ValidationBuilder $validationBuilder,
        ValidatorInterface $validator
    ) {
        $this->validationBuilder = $validationBuilder;
        $this->validator = $validator;
    }

    public function createInsertValidation(OfferEntity $offer): Validator
    {
        return $this->createCrudValidation($offer)
            ->getValidator($this->validator);
    }

    public function createUpdateValidation(OfferEntity $offer): Validator
    {
        return $this->createCrudValidation($offer)
            ->validateThat('id', $offer->id)
            ->isInt()

            ->getValidator($this->validator);
    }

    private function createCrudValidation(OfferEntity $offer): ValidationBuilder
    {
        return $this->validationBuilder

            ->validateThat('name', $offer->name)
            ->isNotBlank()
            ->isString();
    }
}
