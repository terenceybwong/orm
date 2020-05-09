<?php

declare(strict_types=1);

namespace Shop;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Cart
 *
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="cart")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $item;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getItem(): string
    {
        return $this->item;
    }

    /**
     * @param string $item
     */
    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
