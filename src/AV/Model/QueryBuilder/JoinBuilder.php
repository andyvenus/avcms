<?php
/**
 * User: Andy
 * Date: 05/11/14
 * Time: 16:44
 */

namespace AV\Model\QueryBuilder;

class JoinBuilder extends QueryBuilderHandler
{
    /**
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return $this
     */
    public function on($key, $operator, $value, $alias)
    {
        return $this->_join($key, $operator, $value, 'AND', $alias);
    }

    /**
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return $this
     */
    public function orOn($key, $operator, $value)
    {
        return $this->_join($key, $operator, $value, 'OR');
    }

    /**
     * @param $key
     * @param null $operator
     * @param null $value
     * @param string $joiner
     *
     * @param null $alias
     * @return $this
     */
    protected function _join($key, $operator = null, $value = null, $joiner = 'AND', $alias = null)
    {
        if ($alias === null || strpos($key, $alias.'.') === false) {
            $key = $this->addTablePrefix($key);
        }

        if ($alias === null || strpos($value, $alias.'.') === false) {
            $value = $this->addTablePrefix($value);
        }

        $this->statements['criteria'][] = compact('key', 'operator', 'value', 'joiner');
        return $this;
    }
}