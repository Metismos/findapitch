<?php

namespace App\Interfaces;

/**
 * Slot Interface
 * 
 * Defines the contract for a slots capabilites
 */
interface SlotInterface
{
    public function getStarts(): ?\DateTimeImmutable;

    public function setStarts(\DateTimeImmutable $starts): self;

    public function getEnds(): ?\DateTimeImmutable;

    public function setEnds(\DateTimeImmutable $ends): self;

    public function getIsAvailable(): ?bool;

    public function setIsAvailable(bool $isAvailable): self;
}