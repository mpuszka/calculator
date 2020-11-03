<?php
declare(strict_types=1);

namespace App;

/**
 * Nonce functionalities interfaces
 */
interface NonceInterface
{   
    /**
     * Check form nonce method
     *
     * @param string $data
     * @return boolean
     */
    public function checkNonce(string $data): bool;
}
