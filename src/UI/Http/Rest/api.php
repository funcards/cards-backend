<?php

/**
 * @OA\Info(
 *     title="FunCards",
 *     version="1.0.0",
 *     @OA\License(
 *         name="MIT",
 *         url="https://github.com/funcards/cards-backend/blob/main/LICENSE"
 *     )
 * )
 *
 * @OA\SecurityScheme(securityScheme="bearerAuth", type="http", scheme="bearer", bearerFormat="JWT")
 *
 * @OA\Schema(schema="pageIndex", type="integer", format="int32", default=0, minimum=0)
 * @OA\Schema(schema="pageSize", type="integer", format="int32", default=1000, minimum=1, maximum=1000)
 *
 * @OA\Schema(schema="userId", type="string", format="uuid")
 * @OA\Schema(schema="email", type="string", format="email")
 * @OA\Schema(schema="boardId", type="string", format="uuid")
 * @OA\Schema(schema="categoryId", type="string", format="uuid")
 * @OA\Schema(schema="tagId", type="string", format="uuid")
 * @OA\Schema(schema="cardId", type="string", format="uuid")
 *
 * @OA\Schema(schema="users", type="array", @OA\Items(ref="#/components/schemas/userId"))
 * @OA\Schema(schema="emails", type="array", @OA\Items(ref="#/components/schemas/email"))
 * @OA\Schema(schema="categories", type="array", @OA\Items(ref="#/components/schemas/categoryId"))
 *
 * @OA\Schema(
 *     schema="error",
 *     type="object",
 *     required={"code", "message"},
 *     @OA\Property(property="status", type="integer", format="int32", description="Status code"),
 *     @OA\Property(property="message", type="string", description="Error message"),
 *     @OA\Property(property="type", type="string", description="Error type"),
 *     @OA\Property(property="title", type="string", description="Error title"),
 * )
 *
 * @OA\Schema(
 *     schema="validationError",
 *     allOf={
 *          @OA\Schema(ref="#/components/schemas/error"),
 *          @OA\Schema(required={"errors"}, @OA\Property(property="errors", required=true, type="object"))
 *     }
 * )
 */
