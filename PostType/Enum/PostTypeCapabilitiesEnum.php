<?php 
namespace Wpint\WPAPI\PostType\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum PostTypeCapabilitiesEnum : string 
{
    use EnumToArray;

    case EDIT_POST = 'edit_post';
    case READ_POST = 'read_post';
    case DELETE_POST = 'delete_post';
    case EDIT_POSTS = 'edit_posts';
    case EDIT_OTHERS_POSTS = 'edit_others_posts';
    case PUBLISH_POSTS = 'publish_posts';
    case CREATE_POSTS = 'create_posts';
    case READ_PRIVATE_POSTS = 'read_private_posts';

}