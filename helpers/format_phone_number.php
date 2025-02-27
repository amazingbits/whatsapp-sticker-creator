<?php

function format_phone_number(string $phoneNumber): ?string
{
    $phoneNumber = preg_replace("/\D/", "", $phoneNumber);

    if (!preg_match("/^\d{10,15}$/", $phoneNumber)) {
        return null;
    }

    if (preg_match("/^(?:55)?(\d{2})(9?)(\d{8})$/", $phoneNumber, $matches)) {
        $ddd = $matches[1];
        $prefix = $matches[2];
        $number = $matches[3];

        if ($prefix === "9") {
            return $ddd . $number;
        }

        return $ddd . $prefix . $number;
    }

    return null;
}