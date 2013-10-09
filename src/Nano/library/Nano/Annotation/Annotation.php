<?php

namespace Nano\Annotation;

class Annotation
{
    public $reflectionClass;

    public function __construct($className)
    {
        $this->reflectionClass = new \ReflectionClass($className);
    }

    public function parse($docComment)
    {

        $docComment = preg_replace('#(/?\ *\*\ */?)#','',$docComment);
        $docComment = preg_replace('#(\n@)#','$1@',$docComment);
        $docComment = array_map(function($annotation) {
            $annotation = str_replace("\n",'',$annotation);
            if (preg_match('#@(\w+)(.+)#', $annotation, $matches))
            {
                return (object)[
                    'tag' => trim($matches[1]),
                    'arguments' => trim($matches[2]),
                ];
            }
            return $annotation;
        },preg_split('#\n@#', $docComment));

        $docComment = array_filter($docComment, function($value){
            return is_object($value);
        });

        return $docComment;
    }

    protected function parseArgs($args)
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