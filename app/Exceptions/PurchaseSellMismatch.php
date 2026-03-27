<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseSellMismatch extends Exception
{
    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @param  array  $guards
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  Request  $request
     * @return Response
     */
    public function render($request)
    {
        $output = ['success' => 0,
            'msg' => $this->getMessage(),
        ];

        if ($request->ajax()) {
            return $output;
        } else {
            throw new Exception($this->getMessage());
        }
    }
}
