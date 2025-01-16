<?php

namespace Domains\Common\Validation;

use Countable;
class MessageBag implements Countable
{
    protected array $messages = [];

    public function add(string $key, Message $message): self
    {
        $this->messages[$key][] = $message;
        return $this;
    }

    public function messages(): array
    {
        return $this->messages;
    }

    public function count(): int
    {
        return count($this->messages());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function toArray(): array
    {
        $result = [];
        /**
         * @var string $key
         * @var array|Message[] $messages
         */
        foreach ($this->messages() as $key => $messages) {
            if (!array_key_exists($key, $result)) {
                $result[$key] = [];
            }

            foreach ($messages As $msg) {
                $result[$key][] = $msg->toArray();
            }
        }

        return $result;
    }
}