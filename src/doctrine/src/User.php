<?php

declare(strict_types=1);

namespace Shop;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grade;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(name="last_purchase", type="date_immutable", nullable=true)
     */
    private $lastPurchase;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="user")
     */
    private $cart;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param int $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastPurchase()
    {
        return $this->lastPurchase;
    }

    /**
     * @param \DateTimeImmutable $lastPurchase
     */
    public function setLastPurchase($lastPurchase)
    {
        $this->lastPurchase = $lastPurchase;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $cart
     */
    public function setCart($cart): void
    {
        $this->cart = $cart;
    }
}
