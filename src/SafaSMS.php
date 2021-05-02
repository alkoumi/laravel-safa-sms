<?php

namespace Alkoumi\LaravelSafaSms;

use Alkoumi\LaravelSafaSms\Handler\Handler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SafaSMS extends Handler
{
    protected $numbers;
    protected $msg;
    protected $removeDuplication = 0;

    /**
     * @param mixed|array|object|Collection $mobileNumbers
     * @return $this
     */
    public function to($mobileNumbers)
    {
        // Get the Numbers from the data
        if ($mobileNumbers instanceof Collection || $mobileNumbers instanceof \Illuminate\Support\Collection || $mobileNumbers instanceof Builder) {
            $this->numbers = $mobileNumbers->pluck('mobile')->toArray();
        } elseif (is_object($mobileNumbers) && isset($mobileNumbers->mobile)) {
            $this->numbers = [$mobileNumbers->mobile];
        } elseif (is_array($mobileNumbers)) {
            $this->numbers = $mobileNumbers;
        } elseif (is_numeric($mobileNumbers)) {
            $this->numbers = [$mobileNumbers];
        } else {
            $content = 'لم نتمكن من استخراج الأرقام من فضلك تأكد من إضافة الارقام المراد إرسالها بشكل صحيح';
            $this->notifyAdmin($content);
            abort(403, $content);
        }

        if (empty($this->numbers)){
            abort(403, 'إنتبه بارك الله فيك! لا يوجد أرقام جوالات صالحة 🤯');
        }

        return $this;
    }

    /**
     * @param string $msgText
     * @return $this
     */
    public function text($msgText)
    {
        if (empty($msgText)){
            abort(403, 'إنتبه بارك الله فيك! لا يوجد أرقام جوالات صالحة 🤯');
        }

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
