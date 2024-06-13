<?php

class DirectoryModel extends Model
{
    public array $main = [
        'id' => null,
        'path' => null,
        'owner_id' => null,
        'parent_id' => null,
        'is_deleted' => null
    ];

    public function __construct(array $fetch)
    {
        foreach ($fetch as $key => $value) {
            $this->main[$key] = $value;
        }
    }
}
