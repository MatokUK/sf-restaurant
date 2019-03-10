<?php

namespace App\Entity;

use App\Restaurant\Availability;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 * @ORM\Table(name="restaurant")
 */
class Restaurant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cuisine;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $description;

    /**
     * @var OpeningInterval[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="OpeningInterval",
     *      mappedBy="restaurant",
     *      orphanRemoval=true,
     *      cascade={"persist"},
     * )
     * @ORM\OrderBy({"dayInWeek": "ASC", "openAt": "ASC"})
     */
    private $openingIntervals;

    public function __construct()
    {
        $this->openingIntervals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(string $cuisine): self
    {
        $this->cuisine = $cuisine;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOpeningIntervals(): Collection
    {
        return $this->openingIntervals;
    }

    public function addOpeningInterval(OpeningInterval $openingInterval): self
    {
        $openingInterval->setRestaurant($this);
        $this->openingIntervals->add($openingInterval);

        return $this;
    }

    public function removeOpeningInterval(OpeningInterval $openingInterval): self
    {
        $this->openingIntervals->removeElement($openingInterval);

        return $this;
    }

    public function isOpen(): bool
    {
        return (new Availability())->isOpen($this);
    }
}
