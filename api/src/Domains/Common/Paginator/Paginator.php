<?php

namespace Domains\Common\Paginator;

class Paginator
{
    public const MAX_PAGE = 2147483647; // Max value for 32-bit integer
    public const MAX_PAGE_LIMIT = 100;
    public const DEFAULT_PAGE_LIMIT = 20;
    private array $items;
    private int $page;
    private int $perPageLimit;
    private int $total;

    public function __construct(array $items, int $total, int $page, int $perPageLimit)
    {
        $this->items = $items;
        $this->page = $page;
        $this->total = $total;
        $this->perPageLimit = $perPageLimit > 0 ? $perPageLimit : 1;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function perPageLimit(): int
    {
        return $this->perPageLimit;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function lastPage(): int
    {
        return max(1, ceil($this->total / $this->perPageLimit));
    }
}