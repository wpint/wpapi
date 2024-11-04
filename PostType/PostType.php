<?php 
namespace Wpint\WPAPI\PostType;

use WP_REST_Autosaves_Controller;
use WP_REST_Posts_Controller;
use Wpint\Contracts\Hook\HookContract;
use Wpint\WPAPI\PostType\Enum\PostTypeCapabilitiesEnum;
use Wpint\WPAPI\PostType\Enum\PostTypeSupportsEnum;
use Illuminate\Support\Str;

/**
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType id()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType name()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType singularName()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType public()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType hasArchive()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType labels()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType description()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType hierarchical()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType excludeFromSearch()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType publiclyQueryable()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType showUI()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType showInMenu()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType showInNavMenus()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType showInAdminBar()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType showInRest()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType restBase()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType restNamespace()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType restControllerClass()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType revisionsRestControllerClass()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType autosaveRestControllerClass()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType lateRouteRegisteration()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType menuPosition()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType menuIcon()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType capabilities()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType capType()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType canExport()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType mapMetaCap()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType supports()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType metaBoxCallback()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType rewrite()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType queryVar()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType deleteWIthUser()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType template()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType templateLock()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType builtIn()
 * @method \Abrz\WPDF\Services\WPAPI\PostType\PostType editLink()
 * @method void register()
 * 
 * @see \Abrz\WPDF\Services\WPAPI\PostType\PostType
 */
class PostType implements HookContract
{

    /**
     * $singularName
     *
     * @var string
     */
    private string $singularName;

	/**
	 * Post type key.
	 *
	 * @since 4.6.0
	 * @var string $name
	 */
	public $name;

	/**
	 * Name of the post type shown in the menu. Usually plural.
	 *
	 * @since 4.6.0
	 * @var string $label
	 */
	public $label;

	/**
	 * Labels object for this post type.
	 *
	 * If not set, post labels are inherited for non-hierarchical types
	 * and page labels for hierarchical ones.
	 *
	 * @see get_post_type_labels()
	 *
	 * @since 4.6.0
	 * @var stdClass $labels
	 */
	public $labels;

	/**
	 * A short descriptive summary of what the post type is.
	 *
	 * Default empty.
	 *
	 * @since 4.6.0
	 * @var string $description
	 */
	public $description;

	/**
	 * Whether a post type is intended for use publicly either via the admin interface or by front-end users.
	 *
	 * While the default settings of $exclude_from_search, $publicly_queryable, $show_ui, and $show_in_nav_menus
	 * are inherited from public, each does not rely on this relationship and controls a very specific intention.
	 *
	 * Default false.
	 *
	 * @since 4.6.0
	 * @var bool $public
	 */
	public $public;

	/**
	 * Whether the post type is hierarchical (e.g. page).
	 *
	 * Default false.
	 *
	 * @since 4.6.0
	 * @var bool $hierarchical
	 */
	public $hierarchical;

	/**
	 * Whether to exclude posts with this post type from front end search
	 * results.
	 *
	 * Default is the opposite value of $public.
	 *
	 * @since 4.6.0
	 * @var bool $exclude_from_search
	 */
	public $exclude_from_search;

	/**
	 * Whether queries can be performed on the front end for the post type as part of `parse_request()`.
	 *
	 * Endpoints would include:
	 *
	 * - `?post_type={post_type_key}`
	 * - `?{post_type_key}={single_post_slug}`
	 * - `?{post_type_query_var}={single_post_slug}`
	 *
	 * Default is the value of $public.
	 *
	 * @since 4.6.0
	 * @var bool $publicly_queryable
	 */
	public $publicly_queryable;

	/**
	 * Whether to generate and allow a UI for managing this post type in the admin.
	 *
	 * Default is the value of $public.
	 *
	 * @since 4.6.0
	 * @var bool $show_ui
	 */
	public $show_ui;

	/**
	 * Where to show the post type in the admin menu.
	 *
	 * To work, $show_ui must be true. If true, the post type is shown in its own top level menu. If false, no menu is
	 * shown. If a string of an existing top level menu ('tools.php' or 'edit.php?post_type=page', for example), the
	 * post type will be placed as a sub-menu of that.
	 *
	 * Default is the value of $show_ui.
	 *
	 * @since 4.6.0
	 * @var bool|string $show_in_menu
	 */
	public $show_in_menu;

	/**
	 * Makes this post type available for selection in navigation menus.
	 *
	 * Default is the value $public.
	 *
	 * @since 4.6.0
	 * @var bool $show_in_nav_menus
	 */
	public $show_in_nav_menus;

	/**
	 * Makes this post type available via the admin bar.
	 *
	 * Default is the value of $show_in_menu.
	 *
	 * @since 4.6.0
	 * @var bool $show_in_admin_bar
	 */
	public $show_in_admin_bar;

	/**
	 * The position in the menu order the post type should appear.
	 *
	 * To work, $show_in_menu must be true. Default null (at the bottom).
	 *
	 * @since 4.6.0
	 * @var int $menu_position
	 */
	public $menu_position;

	/**
	 * The URL or reference to the icon to be used for this menu.
	 *
	 * Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme.
	 * This should begin with 'data:image/svg+xml;base64,'. Pass the name of a Dashicons helper class
	 * to use a font icon, e.g. 'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty
	 * so an icon can be added via CSS.
	 *
	 * Defaults to use the posts icon.
	 *
	 * @since 4.6.0
	 * @var string $menu_icon
	 */
	public $menu_icon;

	/**
	 * The string to use to build the read, edit, and delete capabilities.
	 *
	 * May be passed as an array to allow for alternative plurals when using
	 * this argument as a base to construct the capabilities, e.g.
	 * array( 'story', 'stories' ). Default 'post'.
	 *
	 * @since 4.6.0
	 * @var string $capability_type
	 */
	public $capability_type;

	/**
	 * Whether to use the internal default meta capability handling.
	 *
	 * Default false.
	 *
	 * @since 4.6.0
	 * @var bool $map_meta_cap
	 */
	public $map_meta_cap;

	/**
	 * Provide a callback function that sets up the meta boxes for the edit form.
	 *
	 * Do `remove_meta_box()` and `add_meta_box()` calls in the callback. Default null.
	 *
	 * @since 4.6.0
	 * @var callable $register_meta_box_cb
	 */
	public $register_meta_box_cb;

	/**
	 * An array of taxonomy identifiers that will be registered for the post type.
	 *
	 * Taxonomies can be registered later with `register_taxonomy()` or `register_taxonomy_for_object_type()`.
	 *
	 * Default empty array.
	 *
	 * @since 4.6.0
	 * @var string[] $taxonomies
	 */
	public $taxonomies;

	/**
	 * Whether there should be post type archives, or if a string, the archive slug to use.
	 *
	 * Will generate the proper rewrite rules if $rewrite is enabled. Default false.
	 *
	 * @since 4.6.0
	 * @var bool|string $has_archive
	 */
	public $has_archive;

	/**
	 * Sets the query_var key for this post type.
	 *
	 * Defaults to $post_type key. If false, a post type cannot be loaded at `?{query_var}={post_slug}`.
	 * If specified as a string, the query `?{query_var_string}={post_slug}` will be valid.
	 *
	 * @since 4.6.0
	 * @var string|bool $query_var
	 */
	public $query_var;

	/**
	 * Whether to allow this post type to be exported.
	 *
	 * Default true.
	 *
	 * @since 4.6.0
	 * @var bool $can_export
	 */
	public $can_export;

	/**
	 * Whether to delete posts of this type when deleting a user.
	 *
	 * - If true, posts of this type belonging to the user will be moved to Trash when the user is deleted.
	 * - If false, posts of this type belonging to the user will *not* be trashed or deleted.
	 * - If not set (the default), posts are trashed if post type supports the 'author' feature.
	 *   Otherwise posts are not trashed or deleted.
	 *
	 * Default null.
	 *
	 * @since 4.6.0
	 * @var bool $delete_with_user
	 */
	public $delete_with_user;

	/**
	 * Array of blocks to use as the default initial state for an editor session.
	 *
	 * Each item should be an array containing block name and optional attributes.
	 *
	 * Default empty array.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-templates/
	 *
	 * @since 5.0.0
	 * @var array[] $template
	 */
	public $template;

	/**
	 * Whether the block template should be locked if $template is set.
	 *
	 * - If set to 'all', the user is unable to insert new blocks, move existing blocks
	 *   and delete blocks.
	 * - If set to 'insert', the user is able to move existing blocks but is unable to insert
	 *   new blocks and delete blocks.
	 *
	 * Default false.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-templates/
	 *
	 * @since 5.0.0
	 * @var string|false $template_lock
	 */
	public $template_lock;

	/**
	 * Whether this post type is a native or "built-in" post_type.
	 *
	 * Default false.
	 *
	 * @since 4.6.0
	 * @var bool $_builtin
	 */
	public $_builtin;

	/**
	 * URL segment to use for edit link of this post type.
	 *
	 * Default 'post.php?post=%d'.
	 *
	 * @since 4.6.0
	 * @var string $_edit_link
	 */
	public $_edit_link;

	/**
	 * Post type capabilities.
	 *
	 * @since 4.6.0
	 * @var array $capabilities
	 */
	public $capabilities;

	/**
	 * Triggers the handling of rewrites for this post type.
	 *
	 * Defaults to true, using $post_type as slug.
	 *
	 * @since 4.6.0
	 * @var array|false $rewrite
	 */
	public $rewrite;

	/**
	 * The features supported by the post type.
	 *
	 * @since 4.6.0
	 * @var array|bool $supports
	 */
	public $supports;

	/**
	 * Whether this post type should appear in the REST API.
	 *
	 * Default false. If true, standard endpoints will be registered with
	 * respect to $rest_base and $rest_controller_class.
	 *
	 * @since 4.7.4
	 * @var bool $show_in_rest
	 */
	public $show_in_rest;

	/**
	 * The base path for this post type's REST API endpoints.
	 *
	 * @since 4.7.4
	 * @var string|bool $rest_base
	 */
	public $rest_base;

	/**
	 * The namespace for this post type's REST API endpoints.
	 *
	 * @since 5.9.0
	 * @var string|bool $rest_namespace
	 */
	public $rest_namespace;

	/**
	 * The controller for this post type's REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @since 4.7.4
	 * @var string|bool $rest_controller_class
	 */
	public $rest_controller_class;

	/**
	 * The controller instance for this post type's REST API endpoints.
	 *
	 * Lazily computed. Should be accessed using {@see WP_Post_Type::get_rest_controller()}.
	 *
	 * @since 5.3.0
	 * @var WP_REST_Controller $rest_controller
	 */
	public $rest_controller;

	/**
	 * The controller for this post type's revisions REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @since 6.4.0
	 * @var string|bool $revisions_rest_controller_class
	 */
	public $revisions_rest_controller_class;

	/**
	 * The controller instance for this post type's revisions REST API endpoints.
	 *
	 * Lazily computed. Should be accessed using {@see WP_Post_Type::get_revisions_rest_controller()}.
	 *
	 * @since 6.4.0
	 * @var WP_REST_Controller $revisions_rest_controller
	 */
	public $revisions_rest_controller;

	/**
	 * The controller for this post type's autosave REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @since 6.4.0
	 * @var string|bool $autosave_rest_controller_class
	 */
	public $autosave_rest_controller_class;

	/**
	 * The controller instance for this post type's autosave REST API endpoints.
	 *
	 * Lazily computed. Should be accessed using {@see WP_Post_Type::get_autosave_rest_controller()}.
	 *
	 * @since 6.4.0
	 * @var WP_REST_Controller $autosave_rest_controller
	 */
	public $autosave_rest_controller;

	/**
	 * A flag to register the post type REST API controller after its associated autosave / revisions controllers, instead of before. Registration order affects route matching priority.
	 *
	 * @since 6.4.0
	 * @var bool $late_route_registration
	 */
	public $late_route_registration;

    
    /**
     * Register post type
     *
     * @return void
     */
    public function register()
    {
        add_action('init', function()
        {
            register_post_type($this->name, $this->getArgs());
        });


    }

    /**
     * alias of the name
     *
     * @param string $name
     * @return self
     */
    public function id(string $name) : self
    {
        return $this->name($name);
    }

    /**
     * set $name
     *
     * @param string $name
     * @return self
     */
    public function name(string $name) : self
    {
        $this->name = $name;
        return $this;
    }  
    
    /**
     * set $label
     *
     * @param string $name
     * @return self
     */
    public function label(string $label) : self
    {
        $this->label = $label;
        return $this;
    }  

    /**
     * set $singularName
     *
     * @param string $singularName
     * @return self
     */
    public function singularName(string $singularName) : self
    {
        $this->singularName = $singularName;
        return $this;
    }

    /**
     * set $hasArchive
     *
     * @param boolean $hasArchive
     * @return self
     */
    public function hasArchive(bool $hasArchive = true) : self
    {
        $this->has_archive = $hasArchive;
        return $this;
    }

    /**
     * set $public
     *
     * @return self
     */
    public function public(bool $public = true) : self
    {
        $this->public = $public;
        return $this;
    }


    /**
     * set $hierarchical
     *
     * @return self
     */
    public function hierarchical(bool $hierarchical = true) : self
    {
        $this->hierarchical = $hierarchical;
        return $this;
    }

    /**
     * set $exclude_from_search
     *
     * @return self
     */
    public function excludeFromSearch(bool $exclude = true) : self
    {
        $this->exclude_from_search = isset($exclude) ? $exclude : $this->public;
        return $this;
    }

    /**
     * set $publicly_queryable
     *
     * @return self
     */
    public function publiclyQueryable(bool $queryable = true) : self
    {
        $this->publicly_queryable = isset($queryable) ? $queryable : $this->public;
        return $this;
    }  

    /**
     * set $show_ui
     *
     * @return self
     */
    public function showUI(bool $show = true) : self
    {
        $this->publicly_queryable = isset($show) ? $show : $this->public;
        return $this;
    }  

    /**
     * set $show_in_menu
     *
     * @return self
     */
    public function showInMenu(bool $inMenu = true) : self
    {
        $this->show_in_menu = isset($inMenu) ? $inMenu : $this->public;
        return $this;
    }  

    /**
     * set $show_in_nav_menus
     *
     * @return self
     */
    public function showInNavMenus(bool $inNavMenu = true) : self
    {
        $this->show_in_nav_menus = isset($inNavMenu) ? $inNavMenu : $this->show_in_menu;
        return $this;
    }  

    /**
     * set $show_in_admin_bar
     *
     * @return self
     */
    public function showInAdminBar(bool $inAdminBar = true) : self
    {
        $this->show_in_admin_bar = isset($inAdminBar) ? $inAdminBar : $this->show_in_menu;
        return $this;
    }  
    
    /**
     * set $show_in_rest
     *
     * @return self
     */
    public function showInRest(bool $inRest = true) : self
    {
        $this->show_in_rest = $inRest;
        return $this;
    }  

    /**
     * set $rest_base
     *
     * @return self
     */
    public function restBase(string $restBase) : self
    {
        $this->rest_base = $restBase;
        return $this;
    }  

    /**
     * set $rest_namespace
     *
     * @return self
     */
    public function restNamespace(string $namespace) : self
    {
        $this->rest_namespace = $namespace;
        return $this;
    }  
 
    /**
     * set $rest_controller_class
     *
     * @return self
     */
    public function restControllerClass(string|bool $class) : self
    {
        $this->rest_controller_class = isset($class) ? $class : WP_REST_Posts_Controller::class ;
        return $this;
    }  

    /**
     * set $autosave_rest_controller_class
     *
     * @return self
     */
    public function autosaveRestControllerClass(string|bool $class) : self
    {
        $this->autosave_rest_controller_class = isset($class) ? $class : WP_REST_Autosaves_Controller::class ;
        return $this;
    }  

    /**
     * set $revisions_rest_controller_class
     *
     * @return self
     */
    public function revisionsRestControllerClass(string|bool $class) : self
    {
        $this->revisions_rest_controller_class = isset($class) ? $class : WP_REST_Autosaves_Controller::class ;
        return $this;
    }  

    /**
     * set $late_route_registration
     *
     * @return self
     */
    public function lateRouteRegisteration(bool $late = true) : self
    {
        $this->late_route_registration =  $late;
        return $this;
    }  

    /**
     * set $menu_position
     *
     * @return self
     */
    public function menuPosition(int|null $pos = null) : self
    {
        $this->menu_position =  $pos;
        return $this;
    }  

    /**
     * set $menu_icon
     *
     * @return self
     */
    public function menuIcon(string $icon) : self
    {
        $this->menu_icon =  $icon;
        return $this;
    }  

    /**
     * set $capability_type
     *
     * @return self
     */
    public function capType(string|array $type = 'post') : self
    {
        $this->capability_type =  $type;
        return $this;
    }  

    /**
     * set $capabilities
     *
     * @param string[] $capabilities
     * @return self
     */
    public function capabilities(PostTypeCapabilitiesEnum ...$capabilities) : self
    {
        if($capabilities && is_array($capabilities))
        {
            foreach($capabilities as $c)
            {
                $this->capabilities[] = $c->value;
            }
        }
        return $this;
    }

    /**
     * set $supports
     *
     * @param string[] $supports
     * @return self
     */
    public function supports(PostTypeSupportsEnum ...$supports) : self
    {
        if($supports && is_array($supports))
        {
            foreach($supports as $c)
            {
                $this->supports[] = $c->value;
            }
        }
        return $this;
    }

    /**
     * set map_meta_cap
     *
     * @param boolean $map
     * @return self
     */
    public function mapMetaCap(bool $map = true) : self
    {
        $this->map_meta_cap =  $map;
        return $this;
    }

    /**
     * set register_meta_box_cb
     *
     * @param array $supports
     * @return self
     */
    public function metaBoxCallback(callable $callback) : self
    {
        $this->register_meta_box_cb = $callback ? $callback : null ;
        return $this;
    }

    /**
     * set $taxonomies
     *
     * @param string $taxonomies[]
     * @return self
     */
    public function taxonomies(string ...$taxonomies) : self
    {
        $this->taxonomies = $taxonomies ?? [];
        return $this;
    }
    
    /**
     * set $templates
     *
     * @param string $templates[]
     * @return self
     */
    public function template(string ...$templates) : self
    {
        $this->template = $templates && is_array($templates)  ? $templates : [];
        return $this;
    }
    

    /**
     * set $rewrite
     *
     * @param false|array $rewrite
     * @return self
     */
    public function rewrite(string $slug, bool $withFront = true, bool $pages = true, int $epMask = EP_NONE, ?bool $feeds) : self
    {
        if(!$slug) $this->rewrite = false; 
        $this->rewrite['slug'] = $slug ? $slug : $this->name;
        $this->rewrite['with_front']  = $withFront;
        $this->rewrite['feeds']  = isset($feeds) ? $feeds : $this->has_archive;
        $this->rewrite['pages'] = $pages;
        $this->rewrite['ep_mask'] = $epMask;   
        return $this;
    }

    /**
     * set $queryVar
     *
     * @param boolean $queryVar
     * @return self
     */
    public function queryVar(bool $queryVar = true) : self
    {
        $this->query_var = $queryVar;
        return $this;
    }

    /**
     * set $cat_export
     *
     * @param boolean $export
     * @return self
     */
    public function canExport(bool $export = true) : self
    {
        $this->can_export = $export;
        return $this;
    }

    /**
     * set $delete_with_user
     *
     * If true, posts of this type belonging to the user will be moved to Trash when the user is deleted.
     * If false, posts of this type belonging to the user will *not* be trashed or deleted.
     * If not set (the default), posts are trashed if post type supports the 'author' feature. Otherwise posts are not
     * 
     * @param boolean $delete
     * @return self
     */
    public function deleteWIthUser(bool $delete = null) : self
    {
        $this->delete_with_user = $delete;
        return $this;
    }

    /**
     * set $template_lock
     *
     * @param boolean $lock
     * @return self
     */
    public function templateLock(bool|string $lock = true) : self
    {
        if(is_string($lock) && ($lock == 'insert' || $lock == 'all'))
        {
            $this->template_lock = $lock;
        }else{
            $this->template_lock = false;
        }
        return $this;
    }

    /**
     * set $_builtin
     *
     * @param boolean $buildIn
     * @return self
     */
    public function builtIn(bool $buildIn = false) : self
    {
        $this->_builtin = $buildIn;
        return $this;
    }    

    /**
     * set $_edit_link
     *
     * @param boolean $segment
     * @return self
     */
    public function editLink(string $segment = 'post.php?post=%d') : self
    {
        $this->_builtin = $segment;
        return $this;
    }    

        /**
     * get $name
     *
     * @return string
     */
    public function getName() : string
    {
        return Str::plural(
            Str::replace(['-', '_'], ' ', $this->name)
        );
    }

    /**
     * get $singularName
     *
     * @return string
     */
    public function getSingularName() : string
    {
        return $this->singularName ?? Str::singular(
            Str::replace(['-', '_'], ' ', $this->name)
        );
    }

    /**
     * Get the post type labels
     *
     * @return void
     */
    public function getLabels()
    {
        if($this->labels) return $this->labels;
        $translate_domain = config('app.translate_domain', '');
        
        return array(
            'name'          => __( $this->getName(), $translate_domain),
            'singular_name' => __( $this->getSingularName(), $translate_domain ),
        );
    }

    /**
     * Get post type's args
     *
     * @return void
     */
    public function getArgs()
    {
        $vars = get_object_vars($this);
        foreach($vars as $var => $value)
        {
            if($value === null) unset($vars[$var]);
        }
        $vars['labels'] =   $this->getLabels();
        $vars['label']  =   $this->label ?? $this->name;
        return $vars;
    }

}