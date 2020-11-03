<?php
declare(strict_types=1);

namespace App;

/**
 * Ip class
 */
class Ip 
{   
    /**
     * Get ip method
     *
     * @return string
     */
    public function getIp(): string
    {   
        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        
        return $ip;
    }
}
