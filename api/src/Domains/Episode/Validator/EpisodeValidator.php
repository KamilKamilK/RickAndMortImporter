<?php

namespace Domains\Episode\Validator;

use Domains\Common\Validation\Validator;

class EpisodeValidator extends Validator
{
    public function validateName(string $name): void
    {
        if (empty($name)) {
            $this->exception()->addError('name', 'Name cannot be empty.');
        }
    }

    public function validateAirDate(string $airDate): void
    {
        if (empty($airDate)) {
            $this->exception->addError('date','Air date cannot be empty.');
        }
    }

    public function validateEpisode(string $episode): void
    {
        if (!preg_match('/^S\d{2}E\d{2}$/', $episode)) {
            $this->exception->addError('episode','Episode format is invalid. Expected format: SxxExx.');
        }
    }

    public function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->exception->addError('url','URL is not valid.');
        }
    }
}