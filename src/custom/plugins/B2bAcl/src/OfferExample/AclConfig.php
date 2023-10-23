<?php declare(strict_types=1);

namespace B2bAcl\OfferExample;

class AclConfig
{
    public static function getAclConfigArray(): array
    {
        return [
            'offerexample' => [
                    'B2bOfferExample' => [
                            'index' => 'list',
                            'grid' => 'list',
                            'create' => 'create',
                            'update' => 'update',
                            'remove' => 'delete',
                            'new' => 'create',
                            'detail' => 'detail',
                            'edit' => 'detail',
                        ],
                ],
        ];
    }
}
