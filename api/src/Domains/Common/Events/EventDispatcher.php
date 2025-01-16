<?php

namespace Domains\Common\Events;

use Psr\Log\LoggerInterface;

class EventDispatcher
{
    private static ?EventDispatcher $instance = null;
    private array $listeners = [];
    private LoggerInterface $logger;

    private function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function instance(LoggerInterface $logger): EventDispatcher
    {
        if (self::$instance === null) {
            self::$instance = new self($logger);
        }

        return self::$instance;
    }

    public function addListener(string $eventClass, EventListenerContract $listener): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listener;
    }

    /**
     * @throws \Exception
     */
    public function dispatch(DomainEvent $event): void
    {
        $eventClass = get_class($event);
        $this->logger->info("Dispatching event: " . $eventClass);

        if (isset($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as $listener) {
                try {
                    $listener->handle($event);
                } catch (\Exception $e) {
                    $this->logger->error("Error in listener: " . $e->getMessage());
                    throw $e;
                }
            }
        }
    }
}
