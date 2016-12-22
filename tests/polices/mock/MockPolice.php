<?php

namespace LaraPolicesTests;

use LaraPolices\Polices\AbstractPolice;

class MockPolice extends AbstractPolice
{
    public function mockTrueMethod($request)
    {
        return (bool) ($request->owner_id == $this->user->group['owner_id'] &&
            $request->owner_type == $this->user->group['owner_type']
        );
    }
}
