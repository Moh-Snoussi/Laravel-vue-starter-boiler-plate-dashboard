<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [


        'cardNumber' => $faker->creditCardNumber(),
        'name' => $faker->name(),
        'created_at' => $faker->dateTimeBetween('+0 days', '+2 years'),
        'userId' => $faker->numberBetween(10000, 9999),
        'currentBalance' => $faker->numberBetween(-10000, 999999),
        'operation' => function () {
            $operation = array('deposit', 'withdraw', 'transactionTo', 'transactionFrom');
            return $operation[rand(0, 3)];
        },
        'secondPartyCardNumber' => $faker->creditCardNumber(),
        'secondPartyName' => $faker->name(),
        'amount' => $faker->numberBetween(1, 9999999),
        'reference' => $faker->sentence()
    
        //
    ];
});

