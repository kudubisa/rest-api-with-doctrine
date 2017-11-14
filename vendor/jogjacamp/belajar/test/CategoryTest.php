<?php

use PHPUnit\Framework\TestCase;

Class CategoryTest extends TestCase
{
	public function testRetrieve()
	{
		$srcCat = new \Jogjacamp\Belajar\Category;
		$res = $srcCat->retrieve();

		$this->assertEquals($res,0);

	}
}