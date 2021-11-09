<?php

namespace inc;

class Request
{
    private array $postData;

    public function __construct($postData)
    {
        $this->postData = $postData;
    }

    public function get($key)
    {
        return $this->postData[$key] ?? null;
    }

    public function all(): ?array
    {
        return array_filter($this->postData, function ($value, $key) {
            return $key !== 'action';
        }, 1);
    }

}
