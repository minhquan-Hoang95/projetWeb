<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'l3_panier')]
#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_PRODUCT', columns: ['id_user', 'id_product'])]
#[UniqueEntity(
    fields: ['user', 'product'],
    message: 'cette relation est déjà présente',
    errorPath: false,
)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $desire_quantity = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(name: 'id_user',nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(name: 'id_product',nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesireQuantity(): ?int
    {
        return $this->desire_quantity;
    }

    public function setDesireQuantity(int $desire_quantity): static
    {
        $this->desire_quantity = $desire_quantity;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
