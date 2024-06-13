<?php

class FileModel extends Model
{
    public array $main = [
        'id' => null,
        'file_name' => null,
        'owner_name' => null,
        'owner_id' => null,
        'date' => null,
        'dir_id' => null,
        'shared_for' => null,
        'is_deleted' => null
    ];
    public function __construct(array $fetch)
    {
        foreach ($fetch as $key => $value) {
            $this->main[$key] = $value;
        }
    }

}