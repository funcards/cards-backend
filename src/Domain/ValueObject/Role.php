<?php

declare(strict_types=1);

namespace FC\Domain\ValueObject;

enum Role: string
{
    case User = 'ROLE_USER';
    case ApiUser = 'ROLE_API_USER';
    case Admin = 'ROLE_ADMIN';
    case SuperAdmin = 'ROLE_SUPER_ADMIN';
    case AllowedToSwitch = 'ROLE_ALLOWED_TO_SWITCH';

    case BoardOwner = 'ROLE_BOARD_OWNER';
    case BoardView = 'ROLE_BOARD_VIEW';
    case BoardEdit = 'ROLE_BOARD_EDIT';
    case BoardAddMember = 'ROLE_BOARD_ADD_MEMBER';
    case BoardRemoveMember = 'ROLE_BOARD_REMOVE_MEMBER';
    case CategoryView = 'ROLE_CATEGORY_VIEW';
    case CategoryAdd = 'ROLE_CATEGORY_ADD';
    case CategoryEdit = 'ROLE_CATEGORY_EDIT';
    case CategoryRemove = 'ROLE_CATEGORY_REMOVE';
    case TagView = 'ROLE_TAG_VIEW';
    case TagAdd = 'ROLE_TAG_ADD';
    case TagEdit = 'ROLE_TAG_EDIT';
    case TagRemove = 'ROLE_TAG_REMOVE';
    case CardView = 'ROLE_CARD_VIEW';
    case CardAdd = 'ROLE_CARD_ADD';
    case CardEdit = 'ROLE_CARD_EDIT';
    case CardRemove = 'ROLE_CARD_REMOVE';

    public static function all(): Roles
    {
        return Roles::from(...self::cases());
    }
}
