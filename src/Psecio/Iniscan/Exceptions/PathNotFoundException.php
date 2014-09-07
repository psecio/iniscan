<?php

namespace Psecio\Iniscan\Exceptions;

class PathNotFoundException extends \Exception
{
    protected $message = 'The given php.ini path could not be found';
}
