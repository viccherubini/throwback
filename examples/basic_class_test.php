<?php

require_once dirname(__FILE__). '/basic_class.php';

class BasicClassTest extends ThrowbackTestCase
{

    public function testGettingName()
    {
        $name = 'Vic Cherubini';

        $basicClass = new BasicClass($name);
        $this->assertEquals($name, $basicClass->getName());
    }

}
