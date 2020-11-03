<?php
declare(strict_types=1);

namespace App;

use App\Config;
use App\MetaBox;
use App\Ip;

/**
 * Custom post type class
 */
class Cpt
{   
    /**
     * Constans label of custom post type
     * 
     * @var array
     */
    private const LABEL = [
        'name'                  => 'Calculations',
        'singular_name'         => 'Calculation',
        'menu_name'             => 'Calculations',
        'all_items'             => 'See all',
        'view_item'             => 'See calculation',
        'add_new_item'          => 'Add new',
        'edit_item'             => 'Edit',
        'update_item'           => 'Update',
        'search_items'          => 'Search',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found'
    ];

    /**
     * constans arguments of custom post type
     * 
     * @var array
     */
    private const ARGS = [
        'label'                 => 'Calculations',
        'description'           => 'Calculations',
        'labels'                => self::LABEL,
        'supports'              => [ 
                                    'title', 
                                ],
        'public'                => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'has_archive'           => true,
        'can_export'            => true,
        'exclude_from_search'   => false,
        'yarpp_support'         => true,
        'taxonomies' 	        => ['calculation'],
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'show_in_rest'          => false,
        'menu_icon'             => 'dashicons-calculator'
    ];

    /**
     * Constructor method
     */
    public function __construct() 
    {   
        if (!post_type_exists('calculations'))
        {
            add_action('init', [$this, 'initCpt'], 0);
        }

        $metaBox = new MetaBox;

        add_filter('manage_edit-calculations_columns', [$this, 'addColumns']) ;
        add_action('manage_calculations_posts_custom_column', [$this, 'calculationColumnData'], 10, 2);
    }   

    /**
     * Initialize custom post type method
     *
     * @return void
     */
    public function initCpt(): void
    {   
        register_post_type('calculations', self::ARGS);   
    }
    
    /**
     * Add columns to dashboard view method
     *
     * @return array
     */
    public function addColumns(): array 
    {
        $columns = [
            'cb'    => '&lt;input type="checkbox" />',
            'title' => 'Product'
        ];

        foreach(Config::FIELDS as $key => $field) 
        {
            $columns[$key] = $field;
        }
    
        return $columns;
    }
    
    /**
     * Add data to custom columns method
     *
     * @param string $column
     * @param integer $post_id
     * @return string
     */
    function calculationColumnData(string $column, int $post_id): void 
    {
        $output = '';

        foreach(Config::FIELDS as $key => $field) 
        {
            switch($column)
            {
                case $key:
                    $value     = get_post_meta($post_id, $key, true );
                    $output   .= $value;
                    break;
            }
        }

        echo $output;
    }
    
    /**
     * Add new post with type calcuations method
     *
     * @param array $data
     * @return void
     */
    public function addItem(array $data): void  
    {   
        $myPost = [
            'post_title'        => $data['title'],
            'post_type'         => 'calculations',
            'post_status'       => 'publish',
            'comment_status'    => 'closed', 
            'ping_status'       => 'closed'
        ];

        $data['ip']         = Ip::getIp();
        $data['added-date'] = date('Y-m-d');
           
        $post_id = wp_insert_post($myPost);
        
        foreach (Config::FIELDS as $key => $field)
        {   
            add_post_meta($post_id, $key, $data[$key], true);
        }

        $GLOBALS['calculatorMessage'] = 'Cena produktu ' . $myPost['post_title'] . ',​ wynosi: ' . $data['gross-price'] . '​zł brutto, kwota podatku to ​' . $data['vat-to-pay'] . ' z​ł.';
    }
}
