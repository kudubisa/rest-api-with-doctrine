<?php

use PHPUnit\Framework\TestCase;


Class ClientTest extends TestCase
{

    public function foo()
    {
        return "nothing";
    }

    public function testGreet()
    {

        $client = new \Kudubisa\Essential\Client;
        $res = $client->greet();

        $this->assertEquals($res, 1);

    }

    public function testEnglishToPigLatin()
    {
        $client = new \Kudubisa\Essential\Client;
        $word = "test";
        $expected = "esttay";
        $res = $client->englishToPigLatin($word);

        $this->assertEquals($res, $expected);
    }

    public function testFailureOne()
    {
        $this->assertArrayHasKey('foo', array("bar"=>"boomshakalaka"));
    }


    public function testFailureTwo()
    {
        $this->assertClassHasAttribute('foo', stdClass::class);
    }

    public function testFailureThree()
    {
        $this->assertContains(4, [1,2,3,4,5]);
    }

    public function testHanbyulChoaeyo(){
        $client = new \Kudubisa\Essential\Client;
        $res = $client->hanbyulChoaeyo();
        $expected = "Nan Choaeyo";
        $this->assertEquals($res,);
    }

}
