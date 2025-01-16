<?php

namespace Domains\Common\Validation;

use Domains\Common\Exceptions\DomainValidationException;

class Result
{
    private ?DomainValidationException $error;
    private mixed $value;

    /**
     * @param DomainValidationException|null $error
     * @param mixed|null                     $value
     */
    private function __construct(?DomainValidationException $error = null, mixed $value = null)
    {
        $this->error = $error;
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public static function success(mixed $value): self
    {
        return new self(null, $value);
    }

    /**
     * @param DomainValidationException $error
     * @return self
     */
    public static function failure(DomainValidationException $error): self
    {
        return new self($error, null);
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->error === null;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * @return DomainValidationException|null
     */
    public function error(): ?DomainValidationException
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
    }
}