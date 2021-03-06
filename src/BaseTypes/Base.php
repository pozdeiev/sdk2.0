<?php

/**
 * Copyright (c) 2019. CDEK-IT. All rights reserved.
 * See LICENSE.md for license details.
 *
 * @author Chizhekov Viktor
 */

namespace CdekSDK2\BaseTypes;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Rakit\Validation\Validator;

/**
 * Class Base
 * @package CdekSDK2\BaseTypes
 */
class Base
{
    /**
     * Правила для валидаций
     * @Serializer\Exclude()
     * @var array
     */
    protected $rules = [];

    /**
     * Ошибки валидации
     * @Serializer\SkipWhenEmpty
     * @Type("array")
     * @var array
     */
    protected $errors = [];

    /**
     * Base конструктор
     * @param array $param
     */
    public function __construct(array $param = [])
    {
        foreach ($param as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Валидация правил
     * @return bool
     * @psalm-suppress UndefinedDocblockClass
     */
    public function validate(): bool
    {
        $validator = new Validator();
        $validation = $validator->validate(get_object_vars($this), $this->rules);

        if ($validation->fails()) {
            $this->errors[] = $validation->errors()->all();
        }
        return $validation->passes();
    }

    /**
     * Создание объекта из массива
     * @param array $data
     * @return Base
     */
    public static function create($data = []): self
    {
        \assert(\is_array($data));

        return new static($data);
    }
}
