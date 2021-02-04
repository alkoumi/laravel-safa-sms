<?php

namespace Alkoumi\LaravelSafaSms;

use Alkoumi\LaravelSafaSms\Handler\Handler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SafaSMS extends Handler
{
    protected $numbers = [];
    protected $msg;
    protected $removeDuplication = 0;

    /**
     * @param mixed|array|object|Collection $mobileNumbers
     * @return $this
     */
    public function to($mobileNumbers)
    {
        // $to Numbers Passed is empty
        !$mobileNumbers ?? abort(403, 'إنتبه بارك الله فيك! لم تدخل الأرقام 🤯');

        // Get the Numbers from the data
        if ($mobileNumbers instanceof Collection || $mobileNumbers instanceof \Illuminate\Support\Collection || $mobileNumbers instanceof Builder) {
            $numbers = $mobileNumbers->pluck('mobile')->toArray();
            $this->numbers = array_merge($numbers, $this->numbers);
        } elseif (is_object($mobileNumbers) && isset($mobileNumbers->mobile)) {
            $this->numbers[] = $mobileNumbers->mobile;
        } elseif (is_array($mobileNumbers)) {
            $this->numbers = array_merge($mobileNumbers, $this->numbers);
        } elseif (is_numeric($mobileNumbers)) {
            $this->numbers[] = $mobileNumbers;
        } else {
            $content = 'لم نتمكن من استخراج الأرقام من فضلك تأكد من إضافة ابزرقام المراد إرسالها بشكل صحيح';
            $this->notifyAdmin($content);
            abort(403, $content);
        }

        return $this;
    }

    /**
     * @param string $msgText
     * @return $this
     */
    public function text($msgText)
    {
        !$msgText ?? abort(403, 'إنتبه بارك الله فيك! لم تدخل رسالة لإرسالها 🤯');
        $this->msg = $msgText;
        return $this;
    }

    /**
     * Setting the Formal Sender Name
     * @return $this
     */
    public function asFormal()
    {
        $this->sender = $this->formal_sender;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeDuplication()
    {
        $this->removeDuplication = 1;
        return $this;
    }

    public function send()
    {
        return $this->sendTheMessage();
    }

}
