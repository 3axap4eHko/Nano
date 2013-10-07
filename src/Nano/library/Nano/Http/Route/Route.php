<?php

namespace Nano\Http\Route;

class Route extends AbstractRoute
{
    /**
     * @var string
     */
    protected $pattern;

    public function __construct($name, $pattern, array $defaults = [], array $methods = [])
    {
        $this->setName($name);
        $this->setPattern($pattern);
        $this->setMethods($methods);
        $this->setDefaults($defaults);
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        $this->setExpression($this->compilePattern($pattern));

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function compilePattern($pattern)
    {
        if (preg_match_all('#{(\w+):?([a-zA-Z0-9\.\+\:\\\\\!\?\[\]]*)}#', $pattern, $matches))
        {
            foreach($matches[1] as $idx => $name)
            {
                $regExp = empty($matches[2][$idx]) ? '\w+' : $matches[2][$idx];
                $expression = "($regExp)";
                $pattern = str_replace($matches[0][$idx], $expression, $pattern);
            }
        }

        return "#$pattern#";
    }
}