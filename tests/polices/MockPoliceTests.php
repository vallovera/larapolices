<?php

use LaraPolicesTests\MockPolice;
use Illuminate\Http\Request;
use Illuminate\Auth\GenericUser as User;

class MockPoliceTests extends PHPUnit_Framework_TestCase
{
    public function police()
    {
        return new MockPolice(new User([
            'name' => 'PHPUnit Mocks',
            'email' => 'phpunit@tests.dev',
            'group' => [
                'group_id' => 1,
                'owner_id' => 1,
                'owner_type' => Foo::class
            ]
        ]));
    }

    public function testMockTrueMethodValid()
    {
        $request = new Request();
        $request->replace([
            'owner_id' => 1,
            'owner_type' => Foo::class
        ]);

        $valid = $this->police()->authorize($request, 'mockTrueMethod');
        $this->assertTrue($valid);
    }

    public function testMockTrueMethodInvalid()
    {
        $request = new Request();
        $request->replace([
            'owner_id' => 2,
            'owner_type' => Foo::class
        ]);

        $valid = $this->police()->authorize($request, 'mockTrueMethod');
        $this->assertFalse($valid);
    }
}
