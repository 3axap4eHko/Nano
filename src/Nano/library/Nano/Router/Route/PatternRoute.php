<?php

namespace Nano\Router\Route;

use Nano\stdCls\ArrayCollection;

class PatternRoute extends RegExpRoute
{
    /**
     * @var string
     */
    protected $pattern;
    /**
     * @var array
     */
    protected $valuesMap;

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
            $this->valuesMap = array_flip($matches[1]);
            foreach($matches[1] as $idx => $name)
            {
                $regExp = empty($matches[2][$idx]) ? '\w+' : $matches[2][$idx];
                $expression = "($regExp)";
                $pattern = str_replace($matches[0][$idx], $expression, $pattern);
            }
        }

        return "#$pattern#";
    }

    /**
     * @param ArrayCollection $matches
     *
     * @return ArrayCollection
     */
    protected function mapMatches($matches)
    {
        return new ArrayCollection(array_map(function($value) use ($matches){
            return $matches->get($value);
        }, $this->valuesMap));
    }

    /**
     * @param string $arguments
     *
     * @return bool
     */
    public function handle($arguments)
    {
        if ($matches = $this->getExpression()->match($arguments))
        {
            $matches = $this->mapMatches($matches->slice(1));
            $this->update($matches);

            return true;
        }

        return false;
    }
}