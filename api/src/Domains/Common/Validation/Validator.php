<?php

namespace Domains\Common\Validation;

use Domains\Common\Exceptions\DomainValidationException;

abstract class Validator
{
    protected DomainValidationException $exception;

    public function __construct()
    {
        $this->exception = new DomainValidationException();
    }

    public function exception(): DomainValidationException
    {
        return $this->exception;
    }
}