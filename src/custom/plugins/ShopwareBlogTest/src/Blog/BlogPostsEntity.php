<?php declare(strict_types=1);

namespace ShopwareBlogTest\Blog;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * Entity class
 */
class BlogPostsEntity extends Entity
{
    use EntityIdTrait;

    protected ?int $langId;

    protected ?string $title;

    protected ?string $shortDescription;

    protected ?string $description;

    protected ?string $content;

    /**
     * @return int|null
     */
    public function getLangId(): ?int
    {
        return $this->langId;
    }

    /**
     * @param int|null $langId
     * @return void
     */
    public function setLangId(?int $langId): void
    {
        $this->langId = $langId;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string|null $shortDescription
     * @return void
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

}
