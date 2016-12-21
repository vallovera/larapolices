<?php

use LaraPolicesTests\MockPolice;

class MockPoliceTests extends PHPUnit_Framework_TestCase
{
    public function police()
    {
        return new MockPolice();
    }

    public function testMockTrueMethodValidate()
    {
        $valid = $this->police()->authorize(new \Illuminate\Http\Request(), 'mockTrueMethod');

        $this->assertTrue($valid);
    }

    public function testMockFalseMethodValidate()
    {
        $valid = $this->police()->authorize(new \Illuminate\Http\Request(), 'mockFalseMethod');

        $this->assertFalse($valid);
    }
}