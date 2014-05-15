<?php

class AddTest extends ThrowbackTestCase
{

    public function testAddingRequiresTwoNumbers()
    {
        $command = $this->getCmd(__FILE__, 'add.php');
        $output = $this->runCmd('php', $command);

        $this->assertContains($output, 'Usage: add.php <number1> <number2>');

        $output = $this->runCmd('php', array($command, 1));
        $this->assertContains($output, 'Usage: add.php <number1> <number2>');
    }

    public function testAddingTwoNumbers()
    {
        $number1 = mt_rand(1, 100);
        $number2 = mt_rand(1, 100);
        $sum = $number1 + $number2;

        $command = $this->getCmd(__FILE__, 'add.php');
        $output = $this->runCmd('php', array($command, $number1, $number2));

        $this->assertContains($output, sprintf('The sum of %d and %d is %d', $number1, $number2, $sum));
    }

}
