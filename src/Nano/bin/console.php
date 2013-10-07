<?php


debug($_SERVER);


function debug()
{
    call_user_func_array('var_dump',func_get_args());
    die();
}
