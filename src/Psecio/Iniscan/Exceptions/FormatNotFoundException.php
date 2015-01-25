<?php

namespace Psecio\Iniscan\Exceptions;

class FormatNotFoundException extends \Exception
{
    protected $message = 'The format given is either incorrect or not found';
}
