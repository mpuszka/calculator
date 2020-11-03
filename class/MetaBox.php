<?php
declare(strict_types=1);

namespace App;

use App\Config;
use App\NonceInterface;

/**
 * Admin custom metabox class
 */
class MetaBox implements NonceInterface
{   
    /**
     * Constructor method
     */
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
        add_action('save_post', [$this, 'calculatorFieldsSave'] );
    }

    /**
     * Add metabox method
     *
     * @return void
     */
    public function addMetaBox(): void 
    {
        add_meta_box(
            'calculator_fields', 
            'Calculator fields', 
            [$this, 'calculatorFieldsCallback'], 
            'calculations', 
            'normal', 
            'low', 
            null
        );
    }

    /**
     * Add fields to metabox method
     *
     * @return void
     */
    public function calculatorFieldsCallback(): void
    {
        global $post;
        $prfx_stored_meta = get_post_meta($post->ID);

        wp_nonce_field('calculator_metabox', 'calculator_form_nonce');
        
        foreach (Config::FIELDS as $key => $field)
        {   
            if ('title' === $key) 
            {
                continue;
            }
            
            $type       = ('added-date' === $key) ? 'date' : 'text';
            $fieldValue = (isset($prfx_stored_meta[$key]))  ? $prfx_stored_meta[$key][0] : '';
            
            printf("<div class='form-group form-group-calculator'>
                        <label for='%s'>%s</label>
                        <input id='%s' type='%s' name='%s' value='%s'/>
                    </div>", $key, $field, $key, $type, $key, $fieldValue);
        }
    }
    
    /**
     * Save calculator fields
     *
     * @return void
     */
    public function calculatorFieldsSave(): void
    {           
        if (!$_POST['calculator_form_nonce']) 
        {
            return;
        }
        
        if (false === $this->checkNonce($_POST['calculator_form_nonce']))
        {
            return;
        }

        foreach (Config::FIELDS as $key => $field)
        {
            if (NULL === isset($_POST[$key]))
            {
                continue;
            }

            $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
            update_post_meta( $post->ID, $key, $value);
        }    
    }

    /**
     * Check nonce method
     *
     * @param string $data
     * @return boolean
     */
    public function checkNonce(string $data): bool
    {
        if (false === isset($data)) 
        {
            return false;
        }
        
        if (false === wp_verify_nonce($data, 'calculator_metabox')) 
        {
            return false;
        }

        return true;
    }
}
