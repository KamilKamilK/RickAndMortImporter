<?php

namespace Domains\Common\Validation;

final class Message
{
    private string $message;
    private array $arguments;

    /**
     * @param string $message
     * @param array  $arguments
     */
    public function __construct(string $message, array $arguments = [])
    {
        $this->message = $message;
        $this->arguments = $arguments;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'arguments' => $this->arguments,
        ];
    }
}