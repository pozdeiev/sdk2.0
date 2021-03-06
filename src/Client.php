<?php

/**
 * Copyright (c) 2019. CDEK-IT. All rights reserved.
 * See LICENSE.md for license details.
 *
 * @author Chizhekov Viktor
 */

namespace CdekSDK2;

use CdekSDK2\Actions\Intakes;
use CdekSDK2\Actions\LocationCities;
use CdekSDK2\Actions\LocationRegions;
use CdekSDK2\Actions\Offices;
use CdekSDK2\Actions\Orders;
use CdekSDK2\Actions\Webhooks;
use CdekSDK2\Exceptions\AuthException;
use CdekSDK2\Exceptions\ParsingException;
use CdekSDK2\Http\Api;
use CdekSDK2\Http\ApiResponse;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Client\ClientInterface;

/**
 * Class Client
 * @package CdekSDK2
 */
class Client
{
    /**
     * Объект для взаимодействия с API СДЭК
     * @var Api
     */
    private $http_client;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Orders
     */
    private $orders;

    /**
     * @var Intakes
     */
    private $intakes;

    /**
     * @var Webhooks
     */
    private $webhooks;

    /**
     * @var Offices
     */
    private $offices;

    /**
     * @var LocationRegions
     */
    private $regions;

    /**
     * @var LocationCities
     */
    private $cities;

    /**
     * Client constructor.
     * @param ClientInterface $http
     * @param string|null $account
     * @param string|null $secure
     * @psalm-suppress PropertyTypeCoercion
     */
    public function __construct(ClientInterface $http, string $account = null, string $secure = null)
    {
        $this->http_client = new Api($http, $account, $secure);
        $this->serializer = SerializerBuilder::create()->setPropertyNamingStrategy(
            new SerializedNameAnnotationStrategy(
                new IdenticalPropertyNamingStrategy()
            )
        )->build();
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->http_client->getAccount();
    }

    /**
     * @param string $account
     * @return self
     */
    public function setAccount(string $account): self
    {
        $this->http_client->setAccount($account);
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->http_client->getToken();
    }

    /**
     * @param string $token
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->http_client->setToken($token);
        return $this;
    }

    /**
     * @return string
     */
    public function getSecure(): string
    {
        return $this->http_client->getSecure();
    }

    /**
     * @param string $secure
     * @return self
     */
    public function setSecure(string $secure): self
    {
        $this->http_client->setSecure($secure);
        return $this;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->http_client->isTest();
    }

    /**
     * @param bool $test
     * @return self
     */
    public function setTest(bool $test): self
    {
        $this->http_client->setTest($test);
        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->http_client->isExpired();
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->http_client->getExpire();
    }

    /**
     * Авторизация клиента в сервисе Интеграции
     * @return bool
     * @throws AuthException
     * @throws Exceptions\RequestException
     */
    public function authorize(): bool
    {
        return $this->http_client->authorize();
    }

    /**
     * @return Intakes
     */
    public function intakes(): Intakes
    {
        if ($this->intakes === null) {
            $this->intakes = new Intakes($this->http_client, $this->serializer);
        }
        return $this->intakes;
    }

    /**
     * @return Orders
     */
    public function orders(): Orders
    {
        if ($this->orders === null) {
            $this->orders = new Orders($this->http_client, $this->serializer);
        }
        return $this->orders;
    }

    /**
     * @return Offices
     */
    public function offices(): Offices
    {
        if ($this->offices === null) {
            $this->offices = new Offices($this->http_client, $this->serializer);
        }
        return $this->offices;
    }

    /**
     * @return LocationRegions
     */
    public function regions(): LocationRegions
    {
        if ($this->regions === null) {
            $this->regions = new LocationRegions($this->http_client, $this->serializer);
        }
        return $this->regions;
    }

    /**
     * @return LocationCities
     */
    public function cities(): LocationCities
    {
        if ($this->cities === null) {
            $this->cities = new LocationCities($this->http_client, $this->serializer);
        }
        return $this->cities;
    }

    /**
     * @return Webhooks
     */
    public function webhooks(): Webhooks
    {
        if ($this->webhooks === null) {
            $this->webhooks = new Webhooks($this->http_client, $this->serializer);
        }
        return $this->webhooks;
    }

    /**
     * @param ApiResponse $response
     * @param string $className
     * @return mixed
     * @throws \Exception
     */
    public function formatResponse(ApiResponse $response, string $className)
    {
        if (class_exists($className)) {
            $result = $this->serializer->deserialize($response->getBody(), $className, 'json');
            return $result;
        } else {
            throw new ParsingException('Class ' . $className . ' not found');
        }
    }
}
