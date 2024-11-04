<?php 
namespace Wpint\WPAPI\PostType\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum PostTypeSupportsEnum : string 
{
    use EnumToArray;

    case TITLE = 'manage_post';
    case EDITOR = 'read_post';
    case COMMENTS = 'delete_post';
    case REVISIONS = 'edit_posts';
    case TRACKBACKS = 'edit_others_posts';
    case AUTHOR = 'delete_posts';
    case EXCERPT = 'publish_posts';
    case PAGE_ATTRIBUTES = 'read_private_posts';
    case THUMBNAIL = 'thumbnail';
    case CUSTOM_FIELDS = 'read_private_posts';
    case POST_FORMATS = 'read_private_posts';

}