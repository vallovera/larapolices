<?php

namespace LaraPolices\Polices;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use LaraPolices\Exceptions\ObjectNotFoundException;

abstract class AbstractPolice
{
    /**
     * Define the Autenticatable Interface
     * @var Authenticatable
     */
    protected $user;

    /**
     * @var array Objects storage
     */
    private $objects = array();

    /**
     * AbstractPolice constructor.
     * @param Request $request
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * Store object in police
     *
     * @param mixed $object
     * @return $this
     */
    public function pushObject($object)
    {
        $objectReflection = new \ReflectionClass($object);
        $this->objects[$objectReflection->getShortName()] = $object;

        return $this;
    }

    /**
     * Get object from police
     *
     * @param $name
     * @return mixed
     * @throws ObjectNotFoundException
     */
    public function getObject($name)
    {
        if (!isset($this->objects[$name])) {
            if (class_exists($name)) {
                $this->pushObject(App::make($name));

                return $this->getObject($name);
            }

            throw new ObjectNotFoundException("Object not found.");
        }

        return $this->objects[$name];
    }

    /**
     * Function to call action method to authorize resource permission to user.
     * This function should be return a boolean.
     * @param Request $request Request to validate
     * @param string $actionToValidate Police action to validate
     * @return bool
     */
    public function canMakeAction(Request $request, $actionToValidate)
    {
        return (bool) $this->$actionToValidate($request);
    }
}
