<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\OpenApi;

use OpenApi\Attributes\Response;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class OAResponse extends Response
{
    public static $_required = [];

    public function __construct(
        object|string $ref = Generator::UNDEFINED,
        string|int $response = null,
        ?string $description = null,
        ?array $headers = null,
        $content = null,
        ?array $links = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct(null, $response, $description, $headers, $content, $links, $x, $attachables);

        $this->ref = $ref;
    }
}
