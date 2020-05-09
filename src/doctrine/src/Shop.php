<?php

declare(strict_types=1);

namespace Shop;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__ . '/../bootstrap.php';

class Shop
{
    private const USERS = [
        ['name' => 'Peter'],
        ['name' => 'Paul'],
        ['name' => 'Mary']
    ];
    private const CARTS = [
        [
            'name' => 'Peter',
            'purchase' => [
                ['item' => 'Pen', 'quantity' => 3],
                ['item' => 'Book', 'quantity' => 1],
                ['item' => 'Rubber', 'quantity' => 2]
            ]
        ],
        [
            'name' => 'Paul',
            'purchase' => [
                ['item' => 'Book', 'quantity' => 1]
            ]
        ],
        [
            'name' => 'Mary',
            'purchase' => [
                ['item' => 'Pen', 'quantity' => 5],
                ['item' => 'Book', 'quantity' => 8],
                ['item' => 'Rubber', 'quantity' => 3]
            ]
        ]
    ];

    private $userRepository;

    private $cartRepository;

    private $em;

    public function __construct(EntityManagerInterface $em = null)
    {
        if ($em) {
            $this->userRepository = $em->getRepository(User::class);
            $this->cartRepository = $em->getRepository(Cart::class);
            $this->em = $em;
        }
    }

    public function init()
    {
        foreach (self::USERS as $user) {
            $record = $this->userRepository->findOneBy(['name' => $user['name']]);
            if (!$record) {
                $newUser = new User();
                $newUser->setName($user['name']);
                $this->em->persist($newUser);
            }
        }
        $this->em->flush();
    }

    public function purchase(): void
    {
        foreach (self::CARTS as $cart) {
            /** @var User $user */
            $user = $this->userRepository->findOneBy(['name' => $cart['name']]);
            if (!$user) {
                continue;
            }

            $total = 0;
            foreach ($cart['purchase'] as $purchase) {
                /** @var Cart[]|Collection $userCart */
                $userCart = $user->getCart();
                $item = $purchase['item'];
                $record = $userCart->filter(
                    static function (Cart $p) use ($item) {
                        return $item === $p->getItem();
                    }
                );
                if ($record->count() > 0) {
                    continue;
                }
                $cartItem = new Cart();
                $cartItem->setItem($purchase['item']);
                $cartItem->setQuantity($purchase['quantity']);
                $cartItem->setUser($user);
                $this->em->persist($cartItem);
                $total += $purchase['quantity'];
            }
            $this->grading($cart['name'], $total);
            $user->setLastPurchase(new \DateTimeImmutable());
        }
        $this->em->flush();
    }

    private function grading(string $name, int $total): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['name' => $name]);
        if (!$user) {
            return;
        }
        if ($total > 10) {
            $user->setGrade(2);
        } elseif ($total > 5) {
            $user->setGrade(1);
        } else {
            $user->setGrade(0);
        }
        $this->em->flush();
    }
}

if (isset($entityManager)) {
    $shop = new Shop($entityManager);
    $shop->init();
    $shop->purchase();
}
