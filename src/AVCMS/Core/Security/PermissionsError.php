<?php
/**
 * User: Andy
 * Date: 29/04/2014
 * Time: 19:37
 */

namespace AVCMS\Core\Security;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionsError extends  HttpException
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param integer    $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(200, $message, $previous, array(), $code);
    }
}
