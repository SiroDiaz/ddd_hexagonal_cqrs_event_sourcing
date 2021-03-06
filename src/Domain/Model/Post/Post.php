<?php declare(strict_types=1);

namespace App\Domain\Model\Post;

use App\Common\Domain\AggregateRoot;

class Post extends AggregateRoot
{
    private PostId $postId;
    private string $title;
    private string $content;

    private function __construct(PostId $postId)
    {
        $this->postId = $postId;
    }

    public static function createNewPost(string $title, string $content): self
    {
        $postId = PostId::create();
        $post = new static($postId);
        $post->recordApplyAndPublish(
            new PostWasCreated($postId, $title, $content)
        );
        return $post;
    }

    public static function buildAPost(PostId $postId, string $title, string $content): self
    {
        $post = new static($postId);
        $post->title = $title;
        $post->content = $content;
        return $post;
    }

    public static function deletePost(self $post): self
    {
        $post->recordApplyAndPublish(new PostWasDeleted($post->getPostId()));
        return $post;
    }

    public function applyPostWasCreated(PostWasCreated $postWasCreated): void
    {
        $this->postId = $postWasCreated->postId();
        $this->title = $postWasCreated->title();
        $this->content = $postWasCreated->content();
    }

    public function applyPostWasDeleted(PostWasDeleted $postWasDeleted): void
    {
        $this->postId = $postWasDeleted->postId();
    }

    public function getPostId(): PostId
    {
        return $this->postId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
