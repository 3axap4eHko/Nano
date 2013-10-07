<?php

namespace Nano;

use Nano\stdCls\ArrayCollection;

class Config extends ArrayCollection
{
    /**
     * @param string $directory
     * @param string $name
     * @param string $type
     * @return Config
     */
    public static function fromFile($directory, $name = null, $type = self::TYPE_PHP)
    {
        $fileName = $directory;
        if (!empty($name))
        {
            $fileName.=DIRECTORY_SEPARATOR . $name . $type;
        }
        $config = [];
        if (file_exists($fileName))
        {
            switch($type)
            {
                case self::TYPE_PHP:
                    $config = include $fileName;
                    break;
            }
        }

        return new Config($config);
    }
}