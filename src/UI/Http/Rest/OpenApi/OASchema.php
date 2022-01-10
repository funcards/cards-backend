<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest\OpenApi;

use OpenApi\Attributes\Discriminator;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Xml;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
class OASchema extends Schema
{
    public function __construct(
        object|string $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?string $description = null,
        ?array $required = null,
        ?array $properties = null,
        ?string $type = null,
        ?string $format = null,
        ?Items $items = null,
        ?string $collectionFormat = null,
        $default = null,
        int|string $minimum = Generator::UNDEFINED,
        int|string $maximum = Generator::UNDEFINED,
        ?string $pattern = null,
        ?array $enum = null,
        ?Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        $example = null,
        ?bool $nullable = null,
        ?bool $deprecated = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null,
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct(
            $ref,
            $schema,
            $title,
            $description,
            $required,
            $properties,
            $type,
            $format,
            $items,
            $collectionFormat,
            $default,
            $pattern,
            $enum,
            $discriminator,
            $readOnly,
            $writeOnly,
            $xml,
            $externalDocs,
            $example,
            $nullable,
            $deprecated,
            $allOf,
            $anyOf,
            $oneOf,
            $x,
            $attachables
        );

        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }
}
