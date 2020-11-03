<?php
declare(strict_types=1);

namespace App;

use App\NonceInterface;

/**
 * Calculator class
 */
class Calculator implements NonceInterface
{   
    /**
     * Constans available vat options
     * 
     * @var array
     */
    private const VAT_OPTIONS = [
        '23',
        '22',
        '8',
        '7',
        '5',
        '3',
        '0',
        'zw'
    ];

    /**
     * Constans available form fields
     * 
     * @var array
     */
    private const FORM_FIELDS = [
        'title',
        'net-price',
        'currency',
        'vat'
    ];

    /**
     * Constans available currency
     * 
     * @var array
     */
    private const CURRENCY = [
        'PLN'
    ];

    /**
     * Custom post type object
     *
     * @var object
     */
    private $cpt;

    /**
     * Constructor method
     *
     * @param object $cpt
     */
    public function __construct(object $cpt) 
    {   
        add_shortcode('calculatorForm', [$this, 'calculatorForm']); 
        
        $this->cpt = $cpt;
        $this->SubmitForm();
    }

    /**
     * Create html form for shortcode method
     *
     * @param string $attr
     * @return string
     */
    public function calculatorForm(string $attr): string 
    {   
        $form       = file_get_contents(CHECK__PLUGIN_DIR . 'template/form.php');
        $formNonce  = wp_nonce_field( 'calculator_form', 'calculator_form_nonce' );
		$message    = ( $GLOBALS['calculatorMessage'] ) ? '<div class="alert alert-primary" role="alert">' . $GLOBALS['calculatorMessage'] . '</div>' : '';

        $form = str_replace(
			[ '<%nonce%>', '<%message%>' ],
			[ $formNonce, $message ],
			$form
        );
        
        return $form;
    }

    /**
     * Submit Form method
     *
     * @return void
     */
    private function SubmitForm(): void
    {   
        if (!$_POST['calculator_form_nonce']) 
        {
            return;
        }

        if (false === $this->checkNonce($_POST['calculator_form_nonce']))
        {
            return;
        }

        $data = filter_input_array(INPUT_POST, [
            'title'     => FILTER_SANITIZE_STRING,
            'net-price' => FILTER_SANITIZE_STRING,
            'currency'  => FILTER_SANITIZE_STRING,
            'vat'       => FILTER_SANITIZE_STRING
        ]);

        if (!$this->validateForm($data))
        {
            return;
        }

        if (!in_array($data['currency'], self::CURRENCY))
        {
            $GLOBALS['calculatorMessage'] = 'Not supported currency';

            return;
        }
        
        if (!in_array($data['vat'], self::VAT_OPTIONS))
        {
            $GLOBALS['calculatorMessage'] = 'Not supported vat';

            return;
        }

        unset($GLOBALS['calculatorMessage']);

        $price      = (float) $data['net-price'];
        $vatToPay   = $this->getVat($data);

        $data = array_intersect_key($data, array_flip(self::FORM_FIELDS));
        $data['gross-price']    = $price + $vatToPay;
        $data['vat-to-pay']     = $vatToPay;
        $data['currency']       = 'PLN';
 
        $this->cpt->addItem($data);
    }
    
    /**
     * Validate frontend form method
     *
     * @param array $data
     * @return boolean
     */
    private function validateForm(array $data): bool
    {   
        foreach(self::FORM_FIELDS as $field)
        {
            if (
                !isset($data[$field]) || 
                '' === $data[$field]
            ) {
                $GLOBALS['calculatorMessage'] = 'Field ' . $field . ' is required';

                return false;
            }

            if (!is_numeric($data['net-price']))
            {
                $GLOBALS['calculatorMessage'] = 'Net price supposed to be a number';
                
                return false;
            }
        }

        return true;
    }

    /**
     * Check nonce of form method
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
        
        if (false === wp_verify_nonce($data, 'calculator_form')) 
        {
            return false;
        }

        return true;
    }

    /**
     * Get vat to pay method
     *
     * @param array $data
     * @return float
     */
    public function getVat(array $data): float
    {
        $netPrice   = (float) $data['net-price'];
        $vat        = (float) $data['vat'];

        if ('zw' === $vat) 
        {
            return $netPrice;
        }

        $vatToPay = ($netPrice / 100) * $vat;

        return $vatToPay;
    }
}
