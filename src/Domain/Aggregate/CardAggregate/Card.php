<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CardAggregate;

use Assert\Assert;
use FC\Domain\Aggregate\AggregateRoot;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\EventRecording;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Event\Card\CardCategoryWasChanged;
use FC\Domain\Event\Card\CardContentWasChanged;
use FC\Domain\Event\Card\CardNameWasChanged;
use FC\Domain\Event\Card\CardTagsWasChanged;
use FC\Domain\Event\Card\CardWasCreated;

final class Card implements AggregateRoot
{
    use EventRecording;

    /**
     * @param CardId $id
     * @param BoardId $boardId
     * @param CategoryId $categoryId
     * @param CardName $name
     * @param CardContent $content
     * @param CardTags $tags
     */
    public function __construct(
        private CardId $id,
        private BoardId $boardId,
        private CategoryId $categoryId,
        private CardName $name,
        private CardContent $content,
        private CardTags $tags,
    ) {
    }

    /**
     * @param CardId $cardId
     * @param BoardId $boardId
     * @param CategoryId $categoryId
     * @param CardName $name
     * @param CardContent $content
     * @param CardTags $tags
     * @return static
     */
    public static function create(
        CardId $cardId,
        BoardId $boardId,
        CategoryId $categoryId,
        CardName $name,
        CardContent $content,
        CardTags $tags,
    ): self {
        $card = new self($cardId, $boardId, $categoryId, $name, $content, $tags);
        $card->recordThat(
            new CardWasCreated(
                $cardId->asString(),
                $boardId->asString(),
                $categoryId->asString(),
                $name->asString(),
                $content->asString(),
                $tags->toArray(),
            )
        );
        return $card;
    }

    /**
     * @return CardId
     */
    public function id(): CardId
    {
        return $this->id;
    }

    /**
     * @param CategoryId $newCategoryId
     */
    public function changeCategory(CategoryId $newCategoryId): void
    {
        Assert::that($newCategoryId->asString())->notEq($this->categoryId->asString());

        $this->categoryId = $newCategoryId;

        $this->recordThat(
            new CardCategoryWasChanged($this->id->asString(), $this->boardId->asString(), $newCategoryId->asString())
        );
    }

    /**
     * @param CardName $newName
     */
    public function changeName(CardName $newName): void
    {
        Assert::that($newName->asString())->notEq($this->name->asString());

        $this->name = $newName;

        $this->recordThat(
            new CardNameWasChanged($this->id->asString(), $this->boardId->asString(), $newName->asString())
        );
    }

    /**
     * @param CardContent $newContent
     */
    public function changeContent(CardContent $newContent): void
    {
        Assert::that($newContent->asString())->notEq($this->content->asString());

        $this->content = $newContent;

        $this->recordThat(
            new CardContentWasChanged($this->id->asString(), $this->boardId->asString(), $newContent->asString())
        );
    }

    /**
     * @param CardTags $newTags
     */
    public function changeTags(CardTags $newTags): void
    {
        Assert::that($this->tags->isEqualTo($newTags))->false();

        $this->tags = $newTags;

        $this->recordThat(
            new CardTagsWasChanged($this->id->asString(), $this->boardId->asString(), $newTags->toArray())
        );
    }

    /**
     * @param TagId $tagId
     */
    public function addTag(TagId $tagId): void
    {
        $this->changeTags($this->tags->add($tagId));
    }

    /**
     * @param TagId $tagId
     */
    public function removeTag(TagId $tagId): void
    {
        $this->changeTags($this->tags->remove($tagId));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            'Card: [id => %s, boardId => %s, categoryId => %s, name => %s, tags => %s]',
            $this->id,
            $this->boardId,
            $this->categoryId,
            $this->name,
            $this->tags,
        );
    }
}
