<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 * @ORM\Table(name="restaurant_opening_interval")
 */
class OpeningInterval
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $dayInWeek;

    /**
     * @ORM\Column(type="time")
     */
    private $openAt;

    /**
     * @ORM\Column(type="time")
     */
    private $closeAt;

    /**
     * @var Restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="openingIntervals")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $restaurant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayInWeek(): int
    {
        return $this->dayInWeek;
    }

    public function setDayInWeek(int $dayInWeek): self
    {
        $this->dayInWeek = $dayInWeek;

        return $this;
    }

    public function getOpenAt(): ?\DateTime
    {
        return $this->openAt;
    }

    public function setOpenAt(\DateTime $openAt): self
    {
        $this->openAt = $openAt;

        return $this;
    }

    public function getCloseAt(): ?\DateTime
    {
        return $this->closeAt;
    }

    public function setCloseAt(\DateTime $closeAt): self
    {
        $this->closeAt = $closeAt;

        return $this;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;
        
        return $this;
    }
    
    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }
}