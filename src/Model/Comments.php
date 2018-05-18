<?php

namespace Brendt\Stitcher\Model;

use Brendt\Stitcher\Exception\ValidationException;
use Carbon\Carbon;
use Pageon\Lib\Parsedown;
use Ramsey\Uuid\Uuid;
use Stitcher\App;
use Stitcher\File;

final class Comments
{
    /** @var string */
    private $file;

    /** @var array */
    private $comments;

    /** @var Parsedown */
    private $markdownParser;

    public function __construct(string $postId)
    {
        $this->file = File::path("data/comments/{$postId}.json");

        $this->comments = json_decode(@file_get_contents($this->file), true) ?? [];

        usort($this->comments, function ($a, $b) {
            return ($b['time'] ?? null) <=> ($a['time'] ?? null);
        });

        $this->markdownParser = App::get(Parsedown::class);
    }

    public function all(): array
    {
        return $this->comments;
    }

    public function verified(): array
    {
        return array_filter($this->comments, function ($comment) {
            return $comment['verified'] ?? false;
        });
    }

    public function verify(string $commentId): void
    {
        foreach ($this->comments as &$comment) {
            if ($comment['id'] !== $commentId) {
                continue;
            }

            $comment['verified'] = true;

            break;
        }

        $this->save();
    }

    public function store(string $postId, array $data): array
    {
        $comment = [
            'id' => (string) Uuid::uuid4(),
            'postId' => $postId,
            'email' => $data['email'] ?? null,
            'body' => $this->markdownParser->parse(strip_tags($data['body'] ?? null)),
            'time' => Carbon::now()->toIso8601String(),
            'verified' => false
        ];

        $this->validate($comment);

        $this->comments[] = $comment;

        $this->save();

        return $comment;
    }

    private function validate(array $comment): void
    {
        if (in_array(null, $comment, true)) {
            throw new ValidationException();
        }
    }

    private function save(): void
    {
        file_put_contents($this->file, json_encode($this->comments, JSON_PRETTY_PRINT));
    }
}
