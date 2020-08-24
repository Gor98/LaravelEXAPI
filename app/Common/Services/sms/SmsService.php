<?php

namespace App\Common\Services\sms;

use App\Common\Services\LogService;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Messente\Api\Api\OmnimessageApi;
use Messente\Api\Configuration;
use Messente\Api\Model\Omnimessage;
use Messente\Api\Model\SMS;

/**
 * Class SmsService
 * @package App\Common\Services
 */
class SmsService
{
    /**
     * @var string
     */
    private string $api_username;

    /**
     * @var string
     */
    private string $api_password;

    /**
     * @var bool
     */
    private bool $test_environment = false;

    /**
     * @var OmnimessageApi
     */
    private OmnimessageApi $apiInstance;

    /**
     * @var LogService
     */
    private LogService $logService;

    /**
     * SmsService constructor.
     * @param LogService $logService
     */
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * @param string $api_username
     * @param string $api_password
     * @return SmsService
     */
    public function setCredentials(string $api_username, string $api_password)
    {
        $this->api_username = $api_username;
        $this->api_password = $api_password;

        return $this;
    }

    /**
     * @return $this
     */
    private function buildSender()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername($this->api_username)
            ->setPassword($this->api_password);

        $this->apiInstance = new OmnimessageApi(new Client(), $config);

        return $this;
    }

    /**
     * @param string $to
     * @param string $text
     * @return string
     */
    public function sendSMS(string $to, string $text)
    {
        if ($this->test_environment) {
            return Str::random(8) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(12);
        }

        if (empty($this->apiInstance)) {
            $this->buildSender();
        }

        $message = new Omnimessage(['to' => $to]);
        $sms = new SMS(['text' => $text, 'sender' => config('sms.from')]);
        $message->setMessages([$sms]);

        try {
            $result = $this->apiInstance->sendOmnimessage($message);
            return $result->getOmnimessageId();
        } catch (\Exception $e) {
            $this->logService->log('error', 'message could not be sent through messente', [
                'text' => $text,
                'sender' => config('sms.from'),
            ]);
        }
    }
}
