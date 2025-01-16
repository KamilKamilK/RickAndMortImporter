<?php

namespace Domains\Character\Validator;


use Domains\Common\Validation\Validator;

class CharacterValidator extends Validator
{
    public function validateName(string $name): void
    {
        if (empty($name) || strlen($name) > 100) {
            $this->exception()->addError('name','Name must not be empty and must be less than 100 characters.');
        }
    }

    public function validateStatus(string $status): void
    {
        $allowedStatuses = ['Alive', 'Dead', 'unknown'];
        if (!in_array($status, $allowedStatuses, true)) {
            $this->exception()->addError('status', 'Invalid status provided.');
        }
    }

    public function validateSpecies(string $species): void
    {
        if (empty($species)) {
            $this->exception()->addError('species','Species cannot be empty.');
        }
    }

    public function validateType(?string $type): void
    {
        if ($type !== null && strlen($type) > 250) {
            $this->exception()->addError('type', 'Type must be less than 250 characters.');
        }
    }

    public function validateGender(string $gender): void
    {
        $allowedGenders = ['Male', 'Female', 'Genderless', 'unknown'];
        if (!in_array($gender, $allowedGenders, true)) {
            $this->exception()->addError('gender','Invalid gender provided.');
        }
    }
}