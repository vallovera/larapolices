<?php

namespace LaraPolices\Polices;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbstractPolice
{
    /**
     * Define the Autenticatable Interface
     * @var Authenticatable
     */
    protected $user;

    /**
     * AbstractPolice constructor.
     * @param Request $request
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user = null)
    {
        $this->user = $user;
    }

    /**
     * Function to call action method to authorize resource permission to user.
     * This function should be return a boolean.
     * @param Request $request Request to validate
     * @param string $actionToValidate Police action to validate
     * @return bool
     */
    public function authorize(Request $request, $actionToValidate)
    {
        return (bool) $this->$actionToValidate($request);
    }
}
