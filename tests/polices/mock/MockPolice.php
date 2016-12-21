<?php

namespace LaraPolicesTests;

use LaraPolices\Polices\AbstractPolice;

class MockPolice extends AbstractPolice
{
    public function mockTrueMethod($request)
    {
        if (is_array($request->all())) {
            return ('foo' === 'foo');
        }
    }

    public function mockFalseMethod($request)
    {
        return ('foo' === 'bar');
    }
}