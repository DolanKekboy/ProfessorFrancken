<?php

declare(strict_types=1);

namespace Francken\Domain\Posts;

use Carbon\Carbon;
use Francken\Domain\AggregateRoot;
use Francken\Domain\Posts\Events\DraftDeleted;
use Francken\Domain\Posts\Events\PostCategorized;
use Francken\Domain\Posts\Events\PostContentChanged;
use Francken\Domain\Posts\Events\PostPublishedAt;
use Francken\Domain\Posts\Events\PostTitleChanged;
use Francken\Domain\Posts\Events\PostUnpublished;
use Francken\Domain\Posts\Events\PostWritten;

/// @todo this class still needs logic..
class Post extends AggregateRoot
{
    private $id;
    private $title;
    private $content;
    private $type;
    private $publishedAt;
    private $isDeleted = false;
    // private $authorId;

    // types: PHP needs enums...
    const BLOGPOST = 'blog';
    const NEWSPOST = 'news';

    public static function createDraft(PostId $id, string $title, string $content, PostCategory $type)
    {
        $post = new self();
        $post->apply(new PostWritten($id, $title, $content, $type));
        return $post;
    }

    public function editTitle(string $title)
    {
        $this->apply(new PostTitleChanged($this->id, $title));
    }

    public function editContent(string $content)
    {
        $this->apply(new PostContentChanged($this->id, $content));
    }

    public function categorize(PostCategory $type)
    {
        $this->apply(new PostCategorized($this->id, $type));
    }

    public function setPublishDate(Carbon $date)
    {
        $this->apply(new PostPublishedAt($this->id, $date));
    }

    public function unpublish()
    {
        $this->apply(new PostUnpublished($this->id));
    }

    public function delete()
    {
        $this->apply(new DraftDeleted($this->id));
    }

    public function applyPostWritten(PostWritten $event)
    {
        $this->id = $event->PostId();
        $this->title = $event->title();
        $this->content = $event->content();
        $this->type = $event->type();
    }

    public function applyPostTitleChanged(PostTitleChanged $event)
    {
        $this->title = $event->title();
    }

    public function applyPostContentChanged(PostContentChanged $event)
    {
        $this->content = $event->content;
    }

    public function applyPostCategorized(PostCategorized $event)
    {
        $events->type = $event->type();
    }

    public function applyPostPublishedAt(PostPublishedAt $event)
    {
        $this->published_at = $event->publishedAt();
    }

    public function applyPostUnpublished(PostUnpublished $event)
    {
        $this->published_at = null;
    }

    public function applyDraftDeleted(DraftDeleted $event)
    {
        $this->isDeleted = true;
    }

    public function getAggregateRootId()
    {
        return $this->id;
    }
}
