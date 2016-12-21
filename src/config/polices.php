<?php

/**
 *LARAPOLICES - Laravel Request Polices
 *
 * @author Gilson F. B. Souza <gilsonfernandesbatista@gmail.com>
 */
return [
    /**
    * Configure the default forbidden view to return in normal requests.
    */
    'defaultForbiddenView' => 'errors.403',

    /**
    * Configure the default forbidden message to return in ajax requests.
    */
    'defaultForbiddenMessage' => 'Forbidden',

    /**
     * Configure the default folder of the polices
     */
    'defaultPolicesFolder' => 'App\\Polices\\',
];
