<?php

namespace Nano\Router\Route;

use Nano\stdCls\RegExp;

class RegExpRoute extends AbstractRoute
{
    /**
     * @var RegExp
     */
    protected $expression;

    /**
     * @param \Nano\stdCls\RegExp|string $expression
     *
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = $expression instanceof RegExp ? $expression : new RegExp($expression);

        return $this;
    }

    /**
     * @return \Nano\stdCls\RegExp
     */
    public function getExpression()
    {
        return $this->expression;
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
            $this->update($matches);

            return true;
        }

        return false;
    }
}