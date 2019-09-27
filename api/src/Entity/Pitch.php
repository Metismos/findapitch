<?php

namespace App\Entity;

use App\Interfaces\FacilityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PitchRepository")
 * 
 * @UniqueEntity("name")
 *
 * @OA\Schema(
 *     description="The definition of a pitch.",
 *     type="object",
 *     title="Pitch"
 * )
 */
class Pitch implements FacilityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * 
     * @Assert\Uuid
     * 
     * @Groups({"listPitches", "retrievePitch"})
     * 
     * @OA\Property(type="string", format="uuid")
     */
    private $id;

    /**
     * The name of the pitch.
     * 
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * 
     * @Groups({"listPitches", "retrievePitch"})
     * 
     * @OA\Property(type="string", maxLength=255)
     */
    private $name;

     /**
     * The sport which the pitch caters for.
     * 
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * 
     * @Groups({"listPitches", "retrievePitch"})
     * 
     * @OA\Property(type="string", maxLength=255)
     */
    private $sport;

    /**
     * The slots of time that this pitch is capable of booking out.
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Slot", mappedBy="pitch", orphanRemoval=true, cascade={"persist"}))
     * 
     * @Assert\Collection
     * 
     * @Groups({"addSlots", "listSlots"})
     * 
     * @OA\Property(type="array",
     *      @OA\Items(ref=@Model(type=Slots::class, groups={"retrievePitch"}))
     * )
     */
    private $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): FacilityInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(string $sport): FacilityInterface
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection|Slot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): FacilityInterface
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setPitch($this);
        }

        return $this;
    }

    public function addSlots(array $slots): array
    {
        foreach ($slots as $slot) {
            $this->addSlot($slot);
        }

        return $this->getSlots()->toArray();
    }

    public function removeSlot(Slot $slot): FacilityInterface
    {
        if ($this->slots->contains($slot)) {
            $this->slots->removeElement($slot);
            // set the owning side to null (unless already changed)
            if ($slot->getPitch() === $this) {
                $slot->setPitch(null);
            }
        }

        return $this;
    }
}
