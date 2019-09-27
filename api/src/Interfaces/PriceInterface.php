<?php

namespace App\Interfaces;

/**
 * Price Interface
 * 
 * Defines the contract for a prices capabilites
 */
interface PriceInterface
{
    public function getPrice(): ?float;

    public function setPrice(float $price): self;

    public function getCurrency(): ?string;

    public function setCurrency(string $currency): self;
}