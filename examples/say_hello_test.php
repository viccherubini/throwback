<?php

class SayHelloTest extends ThrowbackTestCase
{

    public function testSayingHello()
    {
        $command = $this->getCmd(__FILE__, 'say_hello.php');
        $output = $this->runCmd('php', $command);

        $this->assertContains($output, 'Hello, world!');
    }

}
