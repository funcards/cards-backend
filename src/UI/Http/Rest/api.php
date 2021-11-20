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
 *     @OA\Property(property="code", type="integer", format="int32", description="Status code"),
 *     @OA\Property(property="message", type="string", description="Error message")
 * )
 *
 * @OA\Schema(
 *     schema="validationError",
 *     allOf={
 *          @OA\Schema(ref="#/components/schemas/error"),
 *          @OA\Schema(required={"details"}, @OA\Property(property="details", required=true, type="array", @OA\Items(type="string")))
 *     }
 * )
 */
