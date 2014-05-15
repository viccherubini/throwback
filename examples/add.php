<?php

if ($argc != 3) {
    echo("Usage: add.php <number1> <number2>\n");
    exit(1);
}

$number1 = (int)$argv[1];
$number2 = (int)$argv[2];
$sum = $number1 + $number2;

echo(sprintf("The sum of %d and %d is %d.\n", $number1, $number2, $sum));
exit(0);
