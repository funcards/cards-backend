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
 * @OA\Schema(schema="boardId", type="string", format="uuid")
 * @OA\Schema(schema="categoryId", type="string", format="uuid")
 * @OA\Schema(schema="tagId", type="string", format="uuid")
 * @OA\Schema(schema="cardId", type="string", format="uuid")
 */
