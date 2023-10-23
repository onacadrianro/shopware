<?php declare(strict_types=1);

namespace DockwareSamplePlugin\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use ShopwareBlogTest\Blog\BlogPostsEntity;

/**
 * Blog Posts Collection Obj
 */
class BlogPostsCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BlogPostsEntity::class;
    }
}
