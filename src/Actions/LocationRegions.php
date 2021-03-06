<?php

declare(strict_types=1);

namespace CdekSDK2\Actions;

/**
 * Class LocationRegions
 * @package CdekSDK2\Actions
 */
class LocationRegions extends Action
{
    use FilteredTrait;

    /**
     * URL для запросов к API
     * @var string
     */
    public const URL = '/location/regions';
}
