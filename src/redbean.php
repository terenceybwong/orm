<?php

declare(strict_types=1);

use \RedBeanPHP\R;

require_once __DIR__ . '/../vendor/autoload.php';

$users = [
    ['name' => 'Peter', 'discount' => 0.9],
    ['name' => 'Paul', 'discount' => 1.0],
    ['name' => 'Mary', 'discount' => 0.95]
];
$carts = [
    [
        'name' => 'Peter',
        'purchase' => [
            ['item' => 'Pen', 'quantity' => 3, 'unitPrice' => 1],
            ['item' => 'Book', 'quantity' => 2, 'unitPrice' => 3],
            ['item' => 'Rubber', 'quantity' => 1, 'unitPrice' => 0.5]
        ]
    ],
    [
        'name' => 'Paul',
        'purchase' => [
            ['item' => 'Book', 'quantity' => 1, 'unitPrice' => 3]
        ]
    ],
    [
        'name' => 'Mary',
        'purchase' => [
            ['item' => 'Pen', 'quantity' => 5, 'unitPrice' => 1],
            ['item' => 'Book', 'quantity' => 9, 'unitPrice' => 3],
            ['item' => 'Rubber', 'quantity' => 2, 'unitPrice' => 0.5]
        ]
    ]
];

function createUsers(array $users)
{
    foreach ($users as $user) {
        $record = R::findOne('user', 'name = ?', [$user['name']]);
        if (!$record) {
            $row = R::dispense('user');
            $row->name = $user['name'];
            $row->discount = $user['discount'];
            R::store($row);
        }
    }
}

function purchase($carts)
{
    foreach ($carts as $cart) {
        $user = R::findOne('user', 'name = ?', [$cart['name']]);
        if (!$user) {
            continue;
        }
        $userCart = $user->ownCartList;
        if ($userCart) {
            continue;
        }
        $total = 0;
        foreach ($cart['purchase'] as $item) {
            $cartItem = R::dispense('cart');
            $cartItem->item = $item['item'];
            $cartItem->quantity = $item['quantity'];
            $cartItem->user_id = $user->id;
            $cartItem->amount = $item['unitPrice'] * $item['quantity'] * $user->discount;
            R::store($cartItem);
            $total += $item['quantity'];
        }
        grading($cart['name'], $total);
        $user->lastPurchase = time();
        R::store($user);
    }
}

function showResult()
{
    $rows = R::getAll(
        'SELECT u.id, u.name, u.grade, c.quantity FROM user u LEFT JOIN cart c on c.user_id=u.id'
    );
    $results = [];
    foreach ($rows as $row) {
        $id = $row['id'];
        if (!isset($results[$id])) {
            $results[$id] = [
                'name' => $row['name'] ?? 'Anonymous',
                'grade' => $row['grade'] ?? 'Unclassified',
                'total' => 0
            ];
        }
        $results[$id]['total'] += $row['quantity'];
    }

    printf("Name\tTotal Purchased\tGrade Attained\n");
    foreach ($results as $id => $result) {
        printf("%s\t%10s\t%10s\n", $result['name'], $result['total'], $result['grade']);
    }
}

function grading(string $name, int $total)
{
    $user = R::findOne('user', 'name = ?', [$name]);
    if ($total > 10) {
        $user->grade = 2;
    } elseif ($total > 5) {
        $user->grade = 1;
    } else {
        $user->grade = 0;
    }
    R::store($user);
}

R::setup('mysql:dbname=redbean', 'redbean', 'redbean');
createUsers($users);
purchase($carts);
showResult();
R::close();
