<?php

use Symfony\Component\HttpClient\HttpClient;

$dotenv = \Dotenv\Dotenv::createUnsafeMutable(__DIR__ . "/../../../");
$dotenv->load();

beforeEach(function () {
    $this->client = HttpClient::create();
});

test('GET / home should return 200', function () {
    $response = $this->client->request('GET', getenv("BASE_URL"));

    expect($response->getStatusCode())->toBe(200);
});

test('GET / invalid route should be 404 in this content', function () {
    $response = $this->client->request('GET', getenv("BASE_URL") . "/aaaa");

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toContain("404");
});

test('POST /api/whatsapp/send should return 200', function () {
    $response = $this->client->request('POST', getenv("BASE_URL") . '/api/whatsapp/send', [
        'json' => [
            'message' => 'Hello World',
            'phone' => '48999999999',
        ]
    ]);

    expect($response->getStatusCode())->toBe(200);
});