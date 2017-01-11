<?php

namespace LaraPolices\Middlewares;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PolicesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actionName = $request->route()->getActionName();

        if ($actionName !== 'Closure') {
            list($controller, $action) = explode('@', $actionName);

            $actionPolice = $this->findActionPolice($controller, $action);

            if ($actionPolice !== null) {
                $police = new $actionPolice(Auth::user());

                if (!$police->canMakeAction($request, $action)) {
                    if ($request->ajax()) {
                        return response()->json(
                            [
                                'error' => Config::get('polices.defaultForbiddenMessage', 'Forbidden')
                            ],
                            Response::HTTP_FORBIDDEN
                        );
                    } else {
                        App::abort(403, Config::get('polices.defaultForbiddenMessage', 'Forbidden'));
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Search the action police class based in the controller name.
     * @param $controller string Namespace of Controller
     * @param $action string Method called by route
     * @return null|string
     */
    private function findActionPolice($controller, $action)
    {
        $policeClass = $this->normalizePoliceName($controller);

        if (class_exists($policeClass) && method_exists($policeClass, $action)) {
            return $policeClass;
        }

        return null;
    }

    /**
     * Normalize the Police Namespace based on controller name.
     * @param $controller string Namespace of Controller
     * @return string
     */
    private function normalizePoliceName($controller)
    {
        return Config::get('polices.defaultPolicesFolder', 'App\\Polices\\').str_replace(
            'Controller',
            '',
            last(explode('\\', $controller))
        ).'Police';
    }
}
