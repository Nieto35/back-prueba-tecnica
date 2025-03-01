<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Market
{
    private ?string $market;
    private const VALID_MARKETS = [
        "AD", "AL", "AM", "BA", "BE", "BG", "BY", "CW", "CY", "CZ", "DK", "EE", "ES", "FI", "FR", "GB", "GE", "GR", "HR", "HU", "IE", "IL", "IS", "IT", "KG", "KZ", "LI", "LT", "LU", "LV", "MC", "MD", "ME", "MK", "MT", "NL", "NO", "PL", "PT", "RO", "RS", "SE", "SI", "SK", "SM", "TJ", "TR", "UA", "UZ", "XK"
    ];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($market)
    {
        if (!is_null($market) && !is_string($market)) {
            throw new InvalidArgumentException('Market must be a string or null.');
        }
        if (!is_null($market)) {
            $market = strtoupper($market);
            if (!in_array($market, self::VALID_MARKETS, true)) {
                throw new InvalidArgumentException('Market must be a valid market code.');
            }
        }
        $this->market = $market;
    }

    public function toString(): ?string
    {
        return $this->market;
    }
}
