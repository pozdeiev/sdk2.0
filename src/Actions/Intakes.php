<?php

/**
 * Copyright (c) 2019. CDEK-IT. All rights reserved.
 * See LICENSE.md for license details.
 *
 * @author Chizhekov Viktor
 */

namespace CdekSDK2\Actions;

use CdekSDK2\BaseTypes\Intake;
use CdekSDK2\Http\ApiResponse;

/**
 * Class Intakes
 * @package CdekSDK2\Actions
 */
class Intakes extends ActionsWithDelete
{
    /**
     * URL для запросов к API
     * @var string
     */
    const URL = '/intakes';

    /**
     * Создание вызова курьера
     * @param Intake $intake
     * @return ApiResponse
     * @throws \CdekSDK2\Exceptions\RequestException
     */
    public function add(Intake $intake): ApiResponse
    {
        $params = $this->serializer->toArray($intake);
        return $this->preparedAdd($params);
    }
}
