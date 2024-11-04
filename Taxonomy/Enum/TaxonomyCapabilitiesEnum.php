<?php 
namespace Wpint\WPAPI\Taxonomy\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum TaxonomyCapabilitiesEnum : string 
{
    use EnumToArray;

    case MANAGE_TERMS = 'manage_terms';
    case EDIT_TERMS = 'edit_terms';
    case DELETE_TERMS = 'delete_terms';
    case ASSING_TERMS = 'assign_terms';

}