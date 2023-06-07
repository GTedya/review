<?php

namespace App\Services;

class CustomVar
{
    /** @var string[] $fields */
    public array $fields;
    /** @var array<string, string> $imageFields */
    public array $imageFields;

    /**
     * @param null|array<string, string> $imageFields
     * Массив, в котором ключ — название поля картинки, а значение — название медиа-коллекции
     */
    public function __construct(?array $fields = [], ?array $imageFields = [])
    {
        $this->fields = $fields ?? [];
        $this->imageFields = $imageFields ?? [];
    }
}
