<?php

namespace AV\Validation\Rules;

abstract class Rule implements RuleInterface {

    protected $ruleData;

    protected $error;

    protected function setError($error, $params = [])
    {
        $this->error = $error;

        if (!empty($params)) {
            $this->ruleData = array_merge($this->ruleData, $params);
        }
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
        if (isset($this->ruleData)) {
            return $this->ruleData;
        }
        else {
            return array();
        }
    }
} 
