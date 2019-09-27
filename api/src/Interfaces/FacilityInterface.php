<?php

namespace App\Interfaces;

use Doctrine\Common\Collections\Collection;
use App\Entity\Slot;

/**
 * Facility Interface
 * 
 * Defines the contract for a facilities capabilites
 */
interface FacilityInterface
{
    public function getName(): ?string;

    public function setName(string $name): self;

    public function getSport(): ?string;

    public function setSport(string $sport): self;

    /**
     * @return Collection|Slot[]
     */
    public function getSlots(): Collection;

    public function addSlot(Slot $slot): self;

    public function addSlots(array $slots): array;

    public function removeSlot(Slot $slot): self;
}