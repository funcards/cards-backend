<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\OpenApi;

use OpenApi\Attributes\Header;
use OpenApi\Attributes\Schema;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS)]
class OAHeader extends Header
{
    public function __construct(
        string|object $ref = Generator::UNDEFINED,
        string $header = Generator::UNDEFINED,
        string $description = Generator::UNDEFINED,
        Schema|string $schema = Generator::UNDEFINED,
        ?array $x = null,
        ?array $attachables = null,
    ) {
        parent::__construct($x, $attachables);

        $this->ref = $ref;
        $this->header = $header;
        $this->description = $description;
        $this->schema = $schema;
    }
}
