<?php

declare(strict_types=1);

use \RedBeanPHP\R;
use \RedBeanPHP\ToolBox;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var ToolBox $db */
$db = R::setup('mysql:dbname=redbean', 'redbean', 'redbean');

$users = [
    ['name' => 'Peter'],
    ['name' => 'Paul'],
    ['name' => 'Mary']
];
$carts = [
    ['name' => 'Peter', 'purchase' => [
	['item' => 'Pen', 'quantity' => 3],
	['item' => 'Book', 'quantity' => 2],
	['item' => 'Rubber', 'quantity' => 1]
    ]],
    ['name' => 'Paul', 'purchase' => [
	['item' => 'Book', 'quantity' => 1]
    ]],
    ['name' => 'Mary', 'purchase' => [
	['item' => 'Pen', 'quantity' => 5],
	['item' => 'Book', 'quantity' => 9],
	['item' => 'Rubber', 'quantity' => 2]
    ]]
];
foreach ($users as $user) {
    $record = R::findOne('user', 'name = ?', [$user['name']]);
    if (!$record) {
        $row = R::dispense('user');
	$row->name = $user['name'];
	R::store($row);
    }
}
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
	R::store($cartItem);
	$total += $item['quantity'];
    }
    grading($cart['name'], $total);
    $user->lastPurchase = time();
    R::store($user);
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

R::close();
