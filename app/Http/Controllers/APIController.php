<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;

class APIController extends Controller
{
    /**
     * @var int
    **/
    protected $stateCode = 200;

    /**
     * @return mixed
    **/
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * @param mixed stateCode
    **/
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;                           // chained calls
    }

    /**
     * Missatges i codis HTTP en format JSON
     * Messages and HTTP codes in JSON
    **/
    public function response($data, $headers = [])
    {
        return Response::json($data, $this->getStateCode(), $headers);
    }

    /**
     * Missatges d'error
     * Error messages
    **/
    public function responseWithError($message)
    {
        return $this->response([
            'error' => [
                'message' => $message,
                'state_code' => $this->getStateCode()
            ]
        ]);
    }

    /**
     * Retorna el codi HTTP 404
     * Returns HTTP code 404
    **/
    public function responseNotFound($message = 'Not found!')
    {
        return $this->setStateCode(404)->responseWithError($message);
    }

    /**
     * Gets the value of a parameter from the URL
     *
     * @param Request $request the URI requested
     * @param string $param parameter to capture
     * @param int $min smallest value when querying for elements per page
     * @param int $max biggest value when querying for elements per page
     * @return int || string || null
     **/
    public function getParam(Request $request, $param, $default=null, $max=null)
    {
        if ( $request->has($param) )
        {
            $getVar = $request->input($param);

            //Is the param a 'page' or 'elements per page' value?
            if ( gettype($getVar) == 'integer' ) {
                if (isset($max) && ($getVar > $max)) {
                    $getVar = $max;
                } elseif (isset($default) && ($getVar < $default)) {
                    $getVar = $default;
                }
            }

            return $getVar;
        }

        return $default;
    }

    /**
     * Constructs a query parameter string, to make the per_page URL
     * parameter value persistent through pages.
     *
     * @param int $currentPage Current pagination page
     * @param int $lastPage Last pagination page
     * @param int $perPage Elements per page
     * @return Array str
     */
    public function persistentPerPage($currentPage, $lastPage, $perPage)
    {
        if ($currentPage > 1)
        {
            $prev = '&per_page=' . $perPage;
        }
        else
        {
            $prev = null;
        }

        if ($currentPage < $lastPage)
        {
            $next = '&per_page=' . $perPage;
        }
        else
        {
            $next = null;
        }

        return ['prev' => $prev,
                'next' => $next];
    }
}
