<?php

declare(strict_types=1);

namespace FC\UI\Http\Rest;

use FC\UI\Http\Rest\OpenApi\OAHeader;
use FC\UI\Http\Rest\OpenApi\OASchema;
use OpenApi\Attributes\Components;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\License;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;

#[OpenApi(
    '3.0.3',
    info: new Info(
        version: '1.0.0',
        title: 'FunCards',
        license: new License('MIT License', url: 'https://github.com/funcards/cards-backend/blob/main/LICENSE'),
    ),
    tags: [
        new Tag(name: 'Authentication', description: 'Authentication API'),
        new Tag(name: 'Boards', description: 'Boards API'),
        new Tag(name: 'Categories', description: 'Categories API'),
        new Tag(name: 'Cards', description: 'Cards API'),
        new Tag(name: 'Members', description: 'Members API'),
        new Tag(name: 'Tags', description: 'Tags API'),
        new Tag(name: 'Users', description: 'Users API'),
    ],
)]
#[SecurityScheme(securityScheme: 'Bearer', type: 'http', bearerFormat: 'JWT', scheme: 'bearer')]
#[Components([
    'schemas' => [
        new OASchema(schema: "pageIndex", type: "integer", format: "int32", default: 0, minimum: 0),
        new OASchema(schema: "pageSize", type: "integer", format: "int32", default: 1000, minimum: 0, maximum: 1000),

        new Schema(schema: 'userId', type: 'string', format: 'uuid'),
        new Schema(schema: 'email', type: 'string', format: 'email'),
        new Schema(schema: 'boardId', type: 'string', format: 'uuid'),
        new Schema(schema: 'categoryId', type: 'string', format: 'uuid'),
        new Schema(schema: 'tagId', type: 'string', format: 'uuid'),
        new Schema(schema: 'cardId', type: 'string', format: 'uuid'),

        new Schema(schema: 'users', type: 'array', items: new Items(ref: '#/components/schemas/userId')),
        new Schema(schema: 'emails', type: 'array', items: new Items(ref: '#/components/schemas/email')),
        new Schema(schema: 'categories', type: 'array', items: new Items(ref: '#/components/schemas/categoryId')),

        new Schema(schema: 'error', required: ['status', 'message'], properties: [
            new Property(property: 'status', description: 'Status code', type: 'integer', format: 'int32'),
            new Property(property: 'message', description: 'Error message', type: 'string'),
            new Property(property: 'type', description: 'Error type', type: 'string'),
            new Property(property: 'title', description: 'Error title', type: 'string'),
        ], type: 'object'),
        new Schema(schema: 'validationError', allOf: [
            new Schema(ref: '#/components/schemas/error'),
            new Schema(
                required: ['errors'],
                properties: [new Property(property: 'errors', type: 'object')],
            ),
        ]),
    ],
    'responses' => [
        new Response(
            response: 'Created',
            description: 'Resource created successfully',
            headers: [
                new OAHeader(
                    header: 'Location',
                    description: 'URL of created resource',
                    schema: new Schema(type: 'string', format: 'uri'),
                ),
            ],
        ),
        new Response(
            response: 'NoContent',
            description: 'No Content',
        ),
        new Response(
            response: 'BadRequest',
            description: 'Bad Request',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
        new Response(
            response: 'Unauthorized',
            description: 'Unauthorized',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
        new Response(
            response: 'Forbidden',
            description: 'Forbidden',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
        new Response(
            response: 'NotFound',
            description: 'Not Found',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
        new Response(
            response: 'Conflict',
            description: 'Conflict',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
        new Response(
            response: 'UnprocessableEntity',
            description: 'Unprocessable Entity',
            content: new JsonContent(ref: '#/components/schemas/validationError'),
        ),
        new Response(
            response: 'InternalServer',
            description: 'Internal Server Error',
            content: new JsonContent(ref: '#/components/schemas/error'),
        ),
    ],
    'parameters' => [
        new Parameter(
            parameter: 'pageIndex',
            name: 'page-index',
            in: 'query',
            schema: new Schema(ref: '#/components/schemas/pageIndex'),
        ),
        new Parameter(
            parameter: 'pageSize',
            name: 'page-size',
            in: 'query',
            schema: new Schema(ref: '#/components/schemas/pageSize'),
        ),
        new Parameter(
            parameter: 'categories',
            name: 'categories',
            in: 'query',
            required: true,
            schema: new Schema(ref: '#/components/schemas/categories'),
        ),
        new Parameter(
            parameter: 'users',
            name: 'users',
            in: 'query',
            required: true,
            schema: new Schema(ref: '#/components/schemas/users'),
        ),
        new Parameter(
            parameter: 'emails',
            name: 'emails',
            in: 'query',
            required: true,
            schema: new Schema(ref: '#/components/schemas/emails'),
        ),
        new Parameter(
            parameter: 'boardId',
            name: 'board-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/boardId'),
        ),
        new Parameter(
            parameter: 'categoryId',
            name: 'category-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/categoryId'),
        ),
        new Parameter(
            parameter: 'tagId',
            name: 'tag-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/tagId'),
        ),
        new Parameter(
            parameter: 'cardId',
            name: 'card-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/cardId'),
        ),
        new Parameter(
            parameter: 'userId',
            name: 'user-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/userId'),
        ),
        new Parameter(
            parameter: 'memberId',
            name: 'member-id',
            in: 'path',
            required: true,
            schema: new Schema(ref: '#/components/schemas/userId'),
        ),
    ],
])]
class RestApi
{
}
