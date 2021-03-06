<?php declare(strict_types=1);

namespace App\Application\Exception;

use Symfony\Component\HttpFoundation\Response;

class ForumNotFoundException extends \Exception
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Forum %s not found', $id), Response::HTTP_NOT_FOUND);
    }
}