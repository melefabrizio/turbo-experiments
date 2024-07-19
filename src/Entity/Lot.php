<?php

namespace App\Entity;

use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[Broadcast]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: false)]
    private string $basePrice = '0.0';

    #[ORM\Column(nullable: false)]
    private bool $sold = false;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $authorName = null;

    /**
     * @var Collection<int, Bid>
     */
    #[ORM\OneToMany(
        targetEntity: Bid::class,
        mappedBy: 'lot',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $bids;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $lastBid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastBidder = null;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBasePrice(): string
    {
        return $this->basePrice;
    }

    public function setBasePrice(string $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function isSold(): bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): static
    {
        $this->sold = $sold;

        return $this;
    }

    public function getLastBid(): ?Bid
    {
        return $this->bids->last() ?: null;
    }

    public function nextBidAmount(): string
    {
        $lastBid = $this->getLastBid();
        if ($lastBid === null) {
            return $this->basePrice;
        }

        return (string)($lastBid->getAmount() + 100);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): static
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setLot($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): static
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getLot() === $this) {
                $bid->setLot(null);
            }
        }

        return $this;
    }


    public function getLastBidAmount(): ?string
    {
        return $this->lastBid;
    }

    public function setLastBidAmount(?string $lastBid): static
    {
        $this->lastBid = $lastBid;

        return $this;
    }

    public function getLastBidder(): ?string
    {
        return $this->lastBidder;
    }

    public function setLastBidder(?string $lastBidder): static
    {
        $this->lastBidder = $lastBidder;

        return $this;
    }
}
