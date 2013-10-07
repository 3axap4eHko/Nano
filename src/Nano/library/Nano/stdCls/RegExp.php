<?php

namespace Nano\stdCls;

class RegExp
{
    /**
     * @var string
     */
    protected $expression;

    public function __construct($expression)
    {
        $this->setExpression($expression);
    }

    /**
     * @param string $expression
     *
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function test($string)
    {
        return !!preg_match($this->getExpression(), $string);
    }

    /**
     * @param string $string
     * @return null|ArrayCollection
     */
    public function match($string)
    {
        if (preg_match($this->getExpression(), $string, $matches))
        {
            return new ArrayCollection($matches);
        }

        return null;
    }

    /**
     * @param string $string
     * @return null|ArrayCollection
     */
    public function matchAll($string)
    {
        if (preg_match_all($this->getExpression(), $string, $matches))
        {
            return new ArrayCollection($matches);
        }

        return null;
    }

    /**
     * @param string $subject
     * @param string $replacement
     * @return string
     */
    public function replace($subject, $replacement = '')
    {
        return preg_replace($this->getExpression(), $replacement, $subject);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getExpression();
    }

}