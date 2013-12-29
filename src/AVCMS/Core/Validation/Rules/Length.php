<?php

namespace AVCMS\Core\Validation\Rules;


class Length extends Rule {

    protected $charset = 'UTF-8';

    protected $min_error = "Parameter '{param_name}' must be at least {min} characters long";

    protected $max_error = "Parameter '{param_name}' must be no more than {max} characters long";

    protected $exact_error = "Parameter '{param_name}' must be exactly {min} characters long";

    public function __construct($min = null, $max = null)
    {
        if ($min > $max) {
            throw new RuleInvalidException("The maximum length cannot be higher than the minimum length");
        }
        elseif ($min === null && $max === null) {
            throw new RuleInvalidException("No minimum or maximum length set");
        }

        $this->rule_data['min'] = $min;
        $this->rule_data['max'] = $max;
    }

    public function assert($param)
    {
        if (function_exists('grapheme_strlen') && $this->charset == 'UTF-8') {
            $length = grapheme_strlen($param);
        } elseif (function_exists('mb_strlen')) {
            $length = mb_strlen($param, $this->charset);
        } else {
            $length = strlen($param);
        }

        if (($this->rule_data['max'] == $this->rule_data['min']) && $length != $this->rule_data['min']) {
            $this->setError($this->exact_error);
            return false;
        }

        if ($this->rule_data['max'] !== null && $length > $this->rule_data['max']) {
            $this->setError($this->max_error);
            return false;
        }
        if ($this->rule_data['min'] !== null && $length < $this->rule_data['min']) {
            $this->setError($this->min_error);
            return false;
        }

        return true;
    }
}