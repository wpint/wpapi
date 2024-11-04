<?php 
namespace Wpint\WPAPI\Taxonomy;

use Wpint\Contracts\Hook\HookContract;
use Illuminate\Support\Str;
use Wpint\WPAPI\Taxonomy\Enum\TaxonomyCapabilitiesEnum;

/**
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy name()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy singularName()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy slug()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy labels()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy postType()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy showQuickEdit()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy hierarchical()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy showUI()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy queryVar()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy showAdminCol()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy capabilities()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy _args()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy rewrite()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy sort()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy builtIn()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy defaultTerm()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy metaboxCallback()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy metaboxSanitizeCallback()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy updateCountCallback()
 * 
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Taxonomy\Taxonomy
 */
class Taxonomy implements HookContract
{


	/**
	 * Taxonomy key.
	 *
	 * @since 4.7.0
	 * @var string
	 */
	public $name;

    /**
	 * singular name of the taxonomy shown in the menu.
	 *
	 * @since 4.7.0
	 * @var string
	 */
	public $singularName;
    

    /**
     * $slug
     *
     * @var string
     */
    private string $slug;

	/**
	 * A short descriptive summary of what the taxonomy is for.
	 *
	 * @since 4.7.0
	 * @var string
	 */
	public $description;

	/**
	 * Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $public;

	/**
	 * Whether the taxonomy is publicly queryable.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $publicly_queryable;

	/**
	 * Whether the taxonomy is hierarchical.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $hierarchical;

	/**
	 * Whether to generate and allow a UI for managing terms in this taxonomy in the admin.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_ui;

	/**
	 * Whether to show the taxonomy in the admin menu.
	 *
	 * If true, the taxonomy is shown as a submenu of the object type menu. If false, no menu is shown.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_in_menu;

	/**
	 * Whether the taxonomy is available for selection in navigation menus.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_in_nav_menus;

	/**
	 * Whether to list the taxonomy in the tag cloud widget controls.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_tagcloud;

	/**
	 * Whether to show the taxonomy in the quick/bulk edit panel.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_in_quick_edit;

	/**
	 * Whether to display a column for the taxonomy on its post type listing screens.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $show_admin_column;

	/**
	 * The callback function for the meta box display.
	 *
	 * @since 4.7.0
	 * @var bool|callable
	 */
	public $meta_box_cb;

	/**
	 * The callback function for sanitizing taxonomy data saved from a meta box.
	 *
	 * @since 5.1.0
	 * @var callable
	 */
	public $meta_box_sanitize_cb;

	/**
	 * An array of object types this taxonomy is registered for.
	 *
	 * @since 4.7.0
	 * @var string[]
	 */
	public $object_type;

	/**
	 * Capabilities for this taxonomy.
	 *
	 * @since 4.7.0
	 * @var stdClass
	 */
	public $cap;

	/**
	 * Rewrites information for this taxonomy.
	 *
	 * @since 4.7.0
	 * @var array|false
	 */
	public $rewrite;

	/**
	 * Query var string for this taxonomy.
	 *
	 * @since 4.7.0
	 * @var string|false
	 */
	public $query_var;

    /**
     * $labels
     *
     * @var array
     */
    private array $labels;

	/**
	 * Function that will be called when the count is updated.
	 *
	 * @since 4.7.0
	 * @var callable
	 */
	public $update_count_callback;

	/**
	 * Whether this taxonomy should appear in the REST API.
	 *
	 * Default false. If true, standard endpoints will be registered with
	 * respect to $rest_base and $rest_controller_class.
	 *
	 * @since 4.7.4
	 * @var bool $show_in_rest
	 */
	public $show_in_rest;

	/**
	 * The base path for this taxonomy's REST API endpoints.
	 *
	 * @since 4.7.4
	 * @var string|bool $rest_base
	 */
	public $rest_base;

	/**
	 * The namespace for this taxonomy's REST API endpoints.
	 *
	 * @since 5.9.0
	 * @var string|bool $rest_namespace
	 */
	public $rest_namespace;

	/**
	 * The controller for this taxonomy's REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @since 4.7.4
	 * @var string|bool $rest_controller_class
	 */
	public $rest_controller_class;

	/**
	 * The controller instance for this taxonomy's REST API endpoints.
	 *
	 * Lazily computed. Should be accessed using {@see WP_Taxonomy::get_rest_controller()}.
	 *
	 * @since 5.5.0
	 * @var WP_REST_Controller $rest_controller
	 */
	public $rest_controller;

	/**
	 * The default term name for this taxonomy. If you pass an array you have
	 * to set 'name' and optionally 'slug' and 'description'.
	 *
	 * @since 5.5.0
	 * @var array|string
	 */
	public $default_term;

	/**
	 * Whether terms in this taxonomy should be sorted in the order they are provided to `wp_set_object_terms()`.
	 *
	 * Use this in combination with `'orderby' => 'term_order'` when fetching terms.
	 *
	 * @since 2.5.0
	 * @var bool|null
	 */
	public $sort;

	/**
	 * Array of arguments to automatically use inside `wp_get_object_terms()` for this taxonomy.
	 *
	 * @since 2.6.0
	 * @var array|null
	 */
	public $args;

	/**
	 * Whether it is a built-in taxonomy.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	public $_builtIn;

    /**
     * $postTypes
     *
     * @var array
     */
    private array $postTypes;

    /**
     * $capabilities
     *
     * @var array
     */
    private array $capabilities;

    /**
     * $_args
     *
     * @var array
     */
    private array $_args;

    /**
     * Register the taxonomy
     *
     * @return void
     */
    public function register()
    {
       add_action( 'init', function(){
            $args = $this->getArgs();
            register_taxonomy( $this->name, $this->postTypes, $args );
        });
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
     * set $slug
     *
     * @param string $slug
     * @return self
     */
    public function slug(string $slug) : self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * set $description
     *
     * @param string $slug
     * @return self
     */
    public function description(string $description) : self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * set $public
     *
     * @param string $slug
     * @return self
     */
    public function public(bool $public = true) : self
    {
        $this->public = $public;
        return $this;
    }

    /**
     * set $publiclyQuariable
     *
     * @param string $slug
     * @return self
     */
    public function publiclyQuariable(bool $publiclyQuariable = true) : self
    {
        $this->publicly_queryable = $publiclyQuariable;
        return $this;
    }

    /**
     * set $labels
     *
     * @param array $labels
     * @return self
     */
    public function labels(array $labels) : self
    {
        $this->labels = $labels;
        return $this;
    }

    /**
     * set $postTypes
     *
     * @param [type] ...$postType
     * @return self
     */
    public function postType(...$postType) : self
    {
        $this->postTypes = $postType;
        return $this;
    }

    /**
     * set $hirarchical
     *
     * @param boolean $hirarchical
     * @return self
     */
    public function hierarchical(bool $hierarchical = true) : self
    {
        $this->hierarchical = $hierarchical;
        return $this;
    }

    /**
     * set $showUI
     *
     * @param boolean $showUI
     * @return self
     */
    public function showUI(bool $show = true) : self
    {
        $this->show_ui = $show;
        return $this;
    }

    /**
     * set $show_in_menu
     *
     * @param boolean $inMenu
     * @return self
     */
    public function showInMenu(bool $inMenu = true) : self
    {
        $this->show_in_menu = $inMenu;
        return $this;
    }
    
    /**
     * set $show_in_rest
     *
     * @param boolean $inRest
     * @return self
     */
    public function showInRest(bool $inRest = true) : self
    {
        $this->show_in_rest = $inRest;
        return $this;
    }

    /**
     * set $restBase
     *
     * @param string $restBase
     * @return self
     */
    public function restBase(string $restBase) : self
    {
        if($restBase) $this->rest_base = $restBase;
        return $this;
    }

    /**
     * set $rest_namespace
     *
     * @param string $restBase
     * @return self
     */
    public function restNamespace(string $restNamespace) : self
    {
        if($restNamespace) $this->rest_namespace = $restNamespace;
        return $this;
    }

    /**
     * set $tagCloud
     *
     * @param bool $show_tagcloud
     * @return self
     */
    public function showTagCloud(bool $ShowTagCloud = true) : self
    {
        $this->show_tagcloud = $ShowTagCloud ? $ShowTagCloud : $this->show_ui;
        return $this;
    }
    
    /**
     * set $show_in_quick_edit
     *
     * @param string $inQuickEdit
     * @return self
     */
    public function showQuickEdit(bool|null $inQuickEdit = true) : self
    {
        $this->show_in_quick_edit =  $inQuickEdit; 
        return $this;
    }

    /**
     * set $meta_box_cb
     *
     * @param mixed $metaboxCallback
     * @return self
     */
    public function metaboxCallback(bool|callable $metaboxCallback = true) : self
    {
        $this->meta_box_cb = $metaboxCallback;
        return $this;
    }
    
    /**
     * set $meta_box_sanitize_cb
     *
     * @param callable $metaboxSanitizeCallback
     * @return self
     */
    public function metaboxSanitizeCallback(callable $sanitizeCallback) : self
    {
        $this->meta_box_sanitize_cb = $sanitizeCallback;
        return $this;
    }
    
    /**
     * set $capabilities
     *
     * @param string[] $capabilities
     * @return self
     */
    public function capabilities(TaxonomyCapabilitiesEnum ...$capabilities) : self
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
     * set $rewrite
     *
     * @param false|array $rewrite
     * @return self
     */
    public function rewrite(string $slug, bool $withFront = true, bool $hirarchical = false, int $epMask = EP_NONE  ) : self
    {
        if(!$slug) $this->rewrite = false; 
        $this->rewrite['slug'] = $slug ? $slug : $this->name;
        $this->rewrite['with_front']  = $withFront;
        $this->rewrite['hierarchical'] = $hirarchical;
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
     * set $show_admin_column
     *
     * @param boolean $showAdminCol
     * @return self
     */
    public function showAdminCol(bool $showAdminCol = true) : self
    {
        $this->show_admin_column = $showAdminCol;
        return $this;
    }

    /**
     * set $updateCountCallback
     *
     * @param callable $callback
     * @return self
     */
    public function updateCountCallback(callable $callback) : self
    {
        $this->update_count_callback = $callback;
        return $this;
    }

    /**
     * set $defaultTerm
     *
     * @param array|string $callback
     * @return self
     */
    public function defaultTerm(string $name, string $slug = null, string $description = '') : self
    {
        if($name) 
        {
            $term['name'] = $name;
            $term['slug'] = $slug ? $slug : Str::slug($name);
            $term['desciption'] = $description;
            $this->default_term = $term;
        };
        return $this;
    }
        
    /**
     * set $sort
     *
     * @param bool $sort
     * @return self
     */
    public function sort(bool|null $sort = true) : self
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * set $_args
     *
     * @param bool $_args
     * @return self
     */
    public function _args(array $_args) : self
    {
        $this->_args = $_args;
        return $this;
    }

    /**
     * set $_builtIn
     *
     * @param bool $buildIn
     * @return self
     */
    public function builtIn(bool $builtIn = true) : self
    {
        $this->_builtIn = $builtIn;
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
     * get $slug
     *
     * @return string
     */
    public function getSlug() : string
    {
        if($this->slug) return Str::slug($this->slug);
        return Str::slug($this->name);
    }

    /**
     * prepare args
     *
     * @return array
     */
    public function getArgs() : array
    {
        $vars = get_object_vars($this);
        foreach($vars as $var => $value)
        {
            if($value === null) unset($vars[$var]);
        }
        $vars['labels'] =   $this->getLabels();
        return $vars;

        return array(
            'labels'            => $this->getLabels(),
            'description'       => $this->description,
            'hierarchical'       => $this->hierarchical, // make it hierarchical (like categories)
            'show_ui'           => $this->show_ui,
            'show_admin_column' => $this->show_admin_column,
            'query_var'         => $this->query_var,
            'public'            => $this->public,
            'publicly_queryable'    => $this->publicly_queryable,
            'show_in_nav_menus'     => $this->show_in_nav_menus,
            'show_in_rest'          => $this->show_in_rest,
            'rest_base'             => $this->rest_base,
            'show_in_rest'          => $this->show_in_rest,
            'rest_namespace'        => $this->rest_namespace,
            'rest_controller_class' => $this->rest_controller_class,
            'show_tagcloud'         => $this->show_tagcloud,
            'show_in_quick_edit'    => $this->show_in_quick_edit,
            'meta_box_cb'           => $this->meta_box_cb,
            'meta_box_sanitize_cb'  => $this->meta_box_sanitize_cb,
            'capabilities'      => $this->capabilities,
            'sort'              => $this->sort,
            'args'              => $this->_args,
            'default_term'      => $this->default_term,
            '_builtin'          => $this->_builtIn,
            'rewrite'           => $this->rewrite,
        );
    }

    /**
     * get $labels
     *
     * @return array
     */
    public function getLabels() : array
    {
        if(isset($this->labels)) return $this->labels;
        $translate_domain = config('app.translate_domain', '');
        return [
            'name'              => _x( $this->getName(), 'taxonomy general name', $translate_domain ),
            'singular_name'     => _x( $this->getSingularName(), 'taxonomy singular name', $translate_domain),
            'search_items'      => __( 'Search ' . $this->getName(), $translate_domain ),
            'all_items'         => __( 'All '. $this->getName(), $translate_domain ),
            'parent_item'       => __( 'Parent ' . $this->getSingularName(), $translate_domain ),
            'parent_item_colon' => __( 'Parent ' . $this->getSingularName() . ' :', $translate_domain),
            'edit_item'         => __( 'Edit ' . $this->getSingularName(), $translate_domain ),
            'update_item'       => __( 'Update ' . $this->getSingularName(), $translate_domain),
            'add_new_item'      => __( 'Add New ' . $this->getSingularName(), $translate_domain),
            'new_item_name'     => __( 'New ' . $this->getSingularName() . ' Name', $translate_domain),
            'menu_name'         => __( $this->getSingularName(), $translate_domain ),
        ];
    }


}

