<?php

namespace AVCMS\Core\Validation\Rules;

abstract class Rule implements RuleInterface {

    protected $rule_data;

    protected $error;

    protected function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        if (isset($this->error)) {
            return $this->error;
        }
        else {
            return null;
        }
    }

    public function getRuleData()
    {
        if (isset($this->rule_data)) {
            return $this->rule_data;
        }
        else {
            return array();
        }
    }
} 