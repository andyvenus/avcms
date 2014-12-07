<?php
/**
 * User: Andy
 * Date: 07/12/14
 * Time: 10:50
 */

namespace AV\Validation\Rules;

class MustExist extends MustNotExist
{
    protected $existsError = "Could not find the value you entered for '{param_name}' in our records";

    public function assert($param)
    {
        $result = $this->getResult($param);

        if (empty($result)) {
            $this->setError($this->existsError);
            return false;
        }
        else {
            return true;
        }
    }
} 