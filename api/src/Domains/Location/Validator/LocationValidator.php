<?php

namespace Domains\Location\Validator;

use Domains\Common\Exceptions\DomainValidationException;
use Domains\Common\Validation\Validator;

class LocationValidator extends Validator
{
    public function validateName(string $name): void
    {
        if (empty($name)) {
            $this->exception->addError('name', 'Location name cannot be empty.');
        }

        if (strlen($name) > 255) {
            $this->exception->addError('name', 'Location name cannot exceed 255 characters.');
        }
    }

    public function validateType(?string $type): void
    {
        if ($type !== null && strlen($type) > 255) {
            $this->exception->addError('type', 'Location type cannot exceed 255 characters.');
        }
    }

    public function validateDimension(?string $dimension): void
    {
        if ($dimension !== null && strlen($dimension) > 255) {
            $this->exception->addError('dimension', 'Location dimension cannot exceed 255 characters.');
        }
    }

    public function validateUrl(string $url): void
    {
        if (empty($url)) {
            $this->exception->addError('url', 'Location URL cannot be empty.');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->exception->addError('url', 'Invalid URL format for location.');
        }
    }
}