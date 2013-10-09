<?php

namespace Nano\Annotation;

use Nano\Annotation\Parser\ParserInterface;
use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceInjection;

/**
 * Class AnnotationManager
 * @package Nano\Annotation
 */
class AnnotationManager implements AnnotationManagerInterface
{
    use ServiceInjection;
    use EventInjection;

    /**
     * @var \ReflectionClass[]
     */
    protected $objects = [];

    public function registerParser($parser, $tagName = null)
    {
        $domain = 'annotation';
        if (!$parser instanceof ParserInterface)
        {
            $domain .= ':parse' . \Nano::toCamelCase($tagName);
        }
        $this->getEventManager()->attach($domain, $parser);

        return $this;
    }

    /**
     * @param AnnotationTag $tag
     *
     * @return $this
     */
    public function callTagParsers(AnnotationTag $tag)
    {
        $this->getEventManager()->fire('annotation:parse' . \Nano::toCamelCase($tag->getName()), $tag);

        return $this;
    }

    /**
     * @param AnnotationTag[] $tags
     *
     * @return $this
     */
    public function callTagsParsers($tags)
    {
        foreach($tags as $tag)
        {
            $this->callTagParsers($tag);
        }

        return $this;
    }

    public function parseObject($object)
    {
        $className = get_class($object);
        if (empty($this->objects[$className]))
        {
            $this->objects[$className] = new \ReflectionClass($object);
            $this->objects[$className]->parsedComents = self::parseDocComment($this->objects[$className]->getDocComment());
        }
        $this->callTagsParsers($this->objects[$className]->parsedComents);

        return $this;
    }

    public function parseObjectProperty()
    {

    }


    public function parseObjectMethod($object, $method)
    {
        $className = get_class($object);
        if (empty($this->objects[$className]))
        {
            $this->objects[$className] = new \ReflectionClass($object);
            if (!isset($this->objects[$className]->parsedMethodsComents))
            {
                $this->objects[$className]->parsedMethodsComents = [];
            }
            if (!isset($this->objects[$className]->parsedMethodsComents[$method]))
            {
                $this->objects[$className]->parsedMethodsComents[$method] = self::parseDocComment($this->objects[$className]->getMethod($method));
            }
        }

        $this->callTagsParsers($this->objects[$className]->parsedMethodsComents[$method]);

        return $this;
    }

    /**
     * @param string $docComment
     *
     * @return AnnotationTag[]
     */
    public static function parseDocComment($docComment)
    {
        // Delete all starts and slashes at the each line beginning
        $docComment = preg_replace('#(^\ */?\**\ */?)#m','',$docComment);
        // Duplicate all @ at the beginning line
        $docComment = preg_replace('#^(@)#m','@$1',$docComment);
        // Group multi line annotations to one line
        $docComment = preg_replace('#\n([^@])#',' $1',$docComment);
        // Create annotations objects
        $docComment = array_map(function($annotation) {
            $annotation = str_replace("\n",'',$annotation);
            $annotation = trim($annotation);

            if (preg_match('#@(\w+)(.+)#', $annotation, $matches))
            {
                return new AnnotationTag(trim($matches[1]), trim($matches[2]));
            }
            return $annotation;
        },preg_split('#^@#m', $docComment));
        // Keep only annotations objects
        $docComment = array_filter($docComment, function($value){
            return $value instanceof AnnotationTag;
        });

        return $docComment;
    }

    public static function parseParameters($args)
    {
        if (preg_match_all('/(\w+)\s*:\s*"?([^"]+)"?/',$args,$m))
        {
            $parsedArgs=[];
            foreach($m[1] as $idx => $key)
            {
                $parsedArgs[$key]=$m[2][$idx];
            }

            return $parsedArgs;
        }

        return [];
    }

}