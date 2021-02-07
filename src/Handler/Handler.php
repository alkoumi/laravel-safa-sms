<?php

namespace Alkoumi\LaravelSafaSms\Handler;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Psr\Http\Message\ResponseInterface;

class Handler
{
    protected $availableBalance;
    protected $errorMessages = [];
    protected $username;
    protected $password;
    protected $sender;


    public function __construct()
    {
        $this->fetchConfig();
    }

    private function fetchConfig(): void
    {
        $config = config('safa-sms');
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
        $this->sender = $this->ads_sender;
    }

    /**
     * @return int the balance number still
     */
    public function getBalance(): int
    {
        if (!$this->setBalance()) {
            $this->notifyAdmin($this->errorMessages['balance']);
            abort(403, $this->errorMessages['balance']);
        }
        return (int)$this->availableBalance;
    }

    /**
     * @return bool
     */
    private function setBalance(): bool
    {
        $endpoint = $this->balanceEndpoient;
        $params = $this->baseParams();

        // Get the Response
        $response = $this->postRequest($endpoint, $params);
        $content = json_decode($response->getBody(), true);

        $code = (int)$content['Code'];
        $message = (string)$content['MessageIs'];
        $this->availableBalance = (int)$content['currentuserpoints'] ?? (int)$content['currentuserpoints'];
        // Check if we have the right Content

        $code ?? abort(403, $content);

        // Check if We Get the balanc
        if ($code !== 117) {
            $this->errorMessages['balance'] = $content['MessageIs'];
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function baseParams(): array
    {
        return $params = [
            'username' => $this->username,
            'password' => $this->password,
            'return' => 'json',
        ];

    }

    /**
     * Send the request to Api using Post method
     *
     * @param string $endpoint
     * @param array $parmas
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function postRequest($endpoint, array $parmas): ResponseInterface
    {
        return $response = $this->client()->post($endpoint, ['form_params' => $parmas]);
    }

    /**
     * @return Client
     */
    private function client(): Client
    {
        return new Client([
            'base_uri' => $this->base_uri,
        ]);
    }

    /**
     * @param $content
     */
    protected function notifyAdmin($content): void
    {
        //Notify Admin in his Mail About the Sending status
        $code = isset($content['Code']) ? (int)$content['Code'] : null;
        $msg = isset($content['MessageIs']) ? (string)$content['MessageIs'] : 'قد يكون تم إرسال الرسالة لكن الخطأ بسبب استخدام الإيموجي 😱';
        $Blocked = isset($content['Blocked']) ? (string)$content['Blocked'] : null;
        $lastuserpoints = isset($content['lastuserpoints']) ? (string)$content['lastuserpoints'] : null;
        $currentuserpoints = isset($content['currentuserpoints']) ? (string)$content['currentuserpoints'] : null;
        $SMSNUmber = isset($content['SMSNUmber']) ? (string)$content['SMSNUmber'] : null;
        $totalcout = isset($content['totalcout']) ? (string)$content['totalcout'] : null;
        $totalsentnumbers = isset($content['totalsentnumbers']) ? (string)$content['totalsentnumbers'] : null;

        Mail::send([], [], function ($message) use ($msg, $Blocked, $lastuserpoints, $currentuserpoints, $SMSNUmber, $totalcout, $totalsentnumbers) {
            $message->to($this->admin_email)
                ->subject('حالة إرسال رسالة من حساب شامل الخاص بك - ' . config('app.name'))
                ->setBody('<h1>' . 'الرسالة : ' . $msg . '</br>' . 'الأرقام المحظورة : ' . $Blocked . '</br>' . 'الرصيد المتبقي  : ' . $currentuserpoints . '</br>' . 'الرسائل المرسلة  : ' . $SMSNUmber . '</br>' . 'الأرقام المرسل إليها  : ' . $totalsentnumbers . '</br>' . Carbon::now() . '</br>' . config('app.name') . '</h1>', 'text/html');
        });
    }

    /**
     * @return string
     */
    protected function sendTheMessage()
    {
        $params = array_merge($this->baseParams(), $this->sendParams());
        $response = $this->postRequest($this->sendEndpoient, $params);
        return $this->getResponseMessage($response);
    }

    /**
     * @return array
     */
    private function sendParams(): array
    {
        $params = [
            'sender' => $this->sender,
            'message' => $this->msg,
            'numbers' => $this->setNumbers($this->numbers),
            'Rmduplicated' => $this->removeDuplication,
        ];

        return $params;
    }

    /**
     * @param array $numbers
     * @return string
     * @throws \Exception
     */
    protected function setNumbers(array $numbers): string
    {
        $numbers = $this->removeDuplicate($numbers);
        return $this->parseNumbers($numbers);
    }

    /**
     * @param array $numbers
     * @return array
     */
    protected function removeDuplicate(array $numbers): array
    {
        return $numbers = array_values(array_unique($numbers));
    }

    /**
     * @param array $numbers
     * @return string
     */
    protected function parseNumbers(array $numbers): string
    {
        $parsedNumbers = '';
        for ($i = 0; $i < count($numbers); $i++) {
            if ((!is_numeric($numbers[$i])) || (strlen($numbers[$i]) != 10) || (substr($numbers[$i], 0, 2) != '05')) {
                $this->notifyAdmin('لابد أن تكون صيغة الأرقام صحيحة مثلا 0500175200 !إنتبه بارك الله فيك');
                abort(403, 'لابد أن تكون صيغة الأرقام صحيحة مثلا 0500175200 !إنتبه بارك الله فيك');
            }
            $parsedNumbers .= '966' . substr($numbers[$i], 1, strlen($numbers[$i]) - 1) . ',';
        }

        $parsedNumbers = substr($parsedNumbers, 0, strlen($parsedNumbers) - 1);
        return $parsedNumbers;
    }

    /**
     * @param $response
     * @return string
     */
    private function getResponseMessage($response)
    {
        $content = json_decode($response->getBody(), true);

        if (!isset($content)) {
            $message = 'قد يكون تم إرسال الرسالة لكن الخطأ بسبب استخدام الإيموجي 😱';
            $this->notifyAdmin($content);
            abort(403, $message);
        }

        $code = (int)$content['Code'];
        $message = (string)$content['MessageIs'];
        //$message = ResponseMsg::{$endpoint}($code);

        $this->notifyAdmin($content);

        if (isset($code) && $code !== 100) {
            abort(403, $message . ' -> كود الخطأ : ' . $code);
        }

        return $message;
    }

}
