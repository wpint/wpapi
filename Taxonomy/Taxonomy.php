<?php 
namespace Wpint\WPAPI\Taxonomy;

use Wpint\Contracts\Hook\HookContract;
use Illuminate\Support\Str;

/**
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy name()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy singularName()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy slug()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy labels()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy postType()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy isHirarchical()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy showUI()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy queryVar()
 * @method \Wpint\WPAPI\Taxonomy\Taxonomy showAdminCol()
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Taxonomy\Taxonomy
 */
class Taxonomy implements HookContract
{

    /**
     * $name
     *
     * @var string
     */
    private string $name;
    
    /**
     * $singularName
     *
     * @var string
     */
    private string $singularName;

    /**
     * $slug
     *
     * @var string
     */
    private string $slug = "";

    /**
     * $hirarchical
     *
     * @var boolean
     */
    private bool $hirarchical = true;

    /**
     * $showUI
     *
     * @var boolean
     */
    private bool $showUI = true;
    
    /**
     * $showAdminCol
     *
     * @var boolean
     */
    private bool $showAdminCol = true;

    /**
     * $queryVar
     *
     * @var boolean
     */
    private bool $queryVar = true;

    /**
     * $labels
     *
     * @var array
     */
    private array $labels = [];

    /**
     * $postTypes
     *
     * @var array
     */
    private array $postTypes = [];

    /**
     * Register the taxonomy
     *
     * @return void
     */
    public function register()
    {
       add_action( 'init', function(){
            $args   = array(
                'hierarchical'      => $this->hirarchical, // make it hierarchical (like categories)
                'labels'            => $this->getLabels(),
                'show_ui'           => $this->showUI,
                'show_admin_column' => $this->showAdminCol,
                'query_var'         => $this->queryVar,
                'rewrite'           => [ 'slug' => $this->getSlug() ],
            );

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
    public function isHirarchical(bool $hirarchical = true) : self
    {
        $this->hirarchical = $hirarchical;
        return $this;
    }

    /**
     * set $showUI
     *
     * @param boolean $showUI
     * @return self
     */
    public function showUI(bool $showUI = true) : self
    {
        $this->showUI = $showUI;
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
        $this->queryVar = $queryVar;
        return $this;
    }

    /**
     * set $showAdminCol
     *
     * @param boolean $showAdminCol
     * @return self
     */
    public function showAdminCol(bool $showAdminCol = true) : self
    {
        $this->showAdminCol = $showAdminCol;
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
     * get $labels
     *
     * @return array
     */
    public function getLabels() : array
    {
        if($this->labels) return $this->labels;
        
        return [
            'name'              => _x( $this->getName(), 'taxonomy general name' ),
            'singular_name'     => _x( $this->getSingularName(), 'taxonomy singular name' ),
            'search_items'      => __( 'Search ' . $this->getName(), '' ),
            'all_items'         => __( 'All '. $this->getName(), '' ),
            'parent_item'       => __( 'Parent ' . $this->getSingularName() ),
            'parent_item_colon' => __( 'Parent ' . $this->getSingularName() . ' :', ''),
            'edit_item'         => __( 'Edit ' . $this->getSingularName(), '' ),
            'update_item'       => __( 'Update ' . $this->getSingularName(), ''),
            'add_new_item'      => __( 'Add New ' . $this->getSingularName(), '' ),
            'new_item_name'     => __( 'New ' . $this->getSingularName() . ' Name', '' ),
            'menu_name'         => __( $this->getSingularName() ),
        ];
    }


}

