<?php

namespace Domains\Common\Exceptions;

use Domains\Common\Validation\Message;
use Domains\Common\Validation\MessageBag;

class DomainValidationException extends \Exception
{
    private MessageBag $messageBag;

    public function __construct(?MessageBag $messageBag = null)
    {
        parent::__construct('Validation exception');
        $this->messageBag = $messageBag ?? new MessageBag();
    }

    public function addError(string $key, string $errorMsg, array $arguments=[]): self
    {
        $this->messageBag->add($key, new Message($errorMsg, $arguments));
        return $this;
    }

    public function hasErrors(): bool
    {
        return !$this->messageBag()->isEmpty();
    }

    public function messageBag(): MessageBag
    {
        return $this->messageBag;
    }

    public function formattedErrors(): array
    {
        $formattedErrors = [];
        foreach ($this->messageBag()->toArray() as $field => $messages) {
            $formattedErrors[$field] = array_map(function($message) {
                return [
                    'message' => $message['message'],
                    'arguments' => $message['arguments']
                ];
            }, $messages);
        }
        return $formattedErrors;
    }
}