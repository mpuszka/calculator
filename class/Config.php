<?php
declare(strict_types=1);

namespace App;

/**
 * Config class
 */
class Config 
{   
    /**
     * Constans with plugin fields type
     */
    public const FIELDS = [
        'net-price'     => 'Net price',
        'currency'      => 'Currency',
        'vat'           => 'Vat',
        'vat-to-pay'    => 'Vat to pay',
        'gross-price'   => 'Gross price',
        'ip'            => 'IP',
        'added-date'    => 'Added date'
    ];
}
