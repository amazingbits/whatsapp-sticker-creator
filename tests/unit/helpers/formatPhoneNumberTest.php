<?php

require_once __DIR__ . "/../../../helpers/format_phone_number.php";

test("with invalid phone number", function () {
    $phoneNumber = "abcd1234";
    $expected = null;
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});

test("with valid phone number with prefix", function () {
    $phoneNumber = "48988889999";
    $expected = "4888889999";
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});

test("with valid phone number without prefix", function () {
    $phoneNumber = "4888889999";
    $expected = "4888889999";
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});

test("with invalid formatted phone number", function () {
    $phoneNumber = "(48) a 999-99";
    $expected = null;
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});

test("with valid and formatted phone number with prefix", function () {
    $phoneNumber = "(48) 9 9999-9999";
    $expected = "4899999999";
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});

test("with valid and formatted phone number without prefix", function () {
    $phoneNumber = "(48) 9999-9999";
    $expected = "4899999999";
    $actual = format_phone_number($phoneNumber);
    expect($actual)->toBe($expected);
});