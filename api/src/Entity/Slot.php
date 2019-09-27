<?php

namespace App\Entity;

use App\Interfaces\PriceInterface;
use App\Interfaces\SlotInterface;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SlotRepository")
 * 
 * @OA\Schema(
 *     description="The definition of a slot.",
 *     type="object",
 *     title="Slot"
 * )
 */
class Slot implements SlotInterface, PriceInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * 
     * @Assert\Uuid
     * 
     * @Groups({"listSlots"})
     * 
     * @OA\Property(type="string", format="uuid")
     */
    private $id;

   /**
     * The time which the slot starts.
     * 
     * @ORM\Column(type="datetime_immutable")
     * 
     * @Assert\DateTime
     * 
     * @Groups({"listSlots", "addSlots"})
     * 
     * @OA\Property(type="string", format="date-time")
     */
    private $starts;

    /**
     * The time which the slot ends.
     * 
     * @ORM\Column(type="datetime_immutable")
     * 
     * @Assert\DateTime
     * 
     * @Groups({"listSlots", "addSlots"})
     * 
     * @OA\Property(type="string", format="date-time")
     */
    private $ends;

    /**
     * The cost of the slot for that time.
     * 
     * @ORM\Column(type="float")
     * 
     * @Assert\Type(type="float")
     * @Assert\PositiveOrZero
     * @Assert\NotBlank
     * 
     * @Groups({"listSlots", "addSlots"})
     * 
     * @OA\Property(type="number")
     */
    private $price = '0.00';

    /**
     * The currency which is being used.
     * 
     * @ORM\Column(type="string", length=3)
     * 
     * @Assert\Currency
     * @Assert\NotBlank
     * 
     * @Groups({"listSlots", "addSlots"})
     * 
     * @OA\Property(type="string")
     */
    private $currency = 'GBP';

    /**
     * Whether or not the slot is currently available.
     * 
     * Set to true as a slot when created will always intially be available.
     * 
     * @ORM\Column(type="boolean")
     * 
     * @Groups({"listSlots", "addSlots"})
     * 
     * @Assert\Type(type="bool")
     * @Assert\IsFalse(message="A slot cannot be added if it's availablity is passed as true, as slots by definition are the holding of a space of time.")
     */
    private $isAvailable = false;

    /**
     * The pitch to which this slot is for.
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Pitch", inversedBy="slots")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Assert\Collection
     * 
     * @OA\Property(ref=@Model(type=Pitch::class))
     */
    private $pitch;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStarts(): ?\DateTimeImmutable
    {
        return $this->starts;
    }

    public function setStarts(\DateTimeImmutable $starts): SlotInterface
    {
        $this->starts = $starts;

        return $this;
    }

    public function getEnds(): ?\DateTimeImmutable
    {
        return $this->ends;
    }

    public function setEnds(\DateTimeImmutable $ends): SlotInterface
    {
        $this->ends = $ends;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): PriceInterface
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): PriceInterface
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPitch(): ?Pitch
    {
        return $this->pitch;
    }

    public function setPitch(?Pitch $pitch): self
    {
        $this->pitch = $pitch;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): SlotInterface
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }
}
