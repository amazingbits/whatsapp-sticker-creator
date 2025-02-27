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