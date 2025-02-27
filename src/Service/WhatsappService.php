<?php

namespace Src\Service;

use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class WhatsappService
{
    private Client $twilioClient;
    private string $twilioNumber;

    public function __construct()
    {
        $this->twilioClient = new Client(getenv("TWILIO_SID"), getenv("TWILIO_TOKEN"));
        $this->twilioNumber = "whatsapp:+" . getenv("TWILIO_PHONE_NUMBER");
    }

    public function sendMessage(string $toNumber, string $message = null, string $mediaUrl = null): MessageInstance
    {
        $data["from"] = $this->twilioNumber;
        if ($message) {
            $data["body"] = $message;
        }
        if ($mediaUrl) {
            $data["mediaUrl"] = [$mediaUrl];
        }
        return $this->twilioClient->messages->create("whatsapp:+55{$toNumber}", $data);
    }
}