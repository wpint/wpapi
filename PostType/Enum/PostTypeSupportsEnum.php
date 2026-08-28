<?php
namespace Wpint\WPAPI\PostType\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum PostTypeSupportsEnum : string
{
    use EnumToArray;

    case TITLE = 'title';
    case EDITOR = 'editor';
    case COMMENTS = 'comments';
    case REVISIONS = 'revisions';
    case TRACKBACKS = 'trackbacks';
    case AUTHOR = 'author';
    case EXCERPT = 'excerpt';
    case PAGE_ATTRIBUTES = 'page-attributes';
    case THUMBNAIL = 'thumbnail';
    case CUSTOM_FIELDS = 'custom-fields';
    case POST_FORMATS = 'post-formats';

}
