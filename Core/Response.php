<?php

class Response
{
    public bool $status;
    public string $message;
    public ?array $data;

    public function __construct(string $status, string $message,?array $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}

