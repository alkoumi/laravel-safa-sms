<p align="center">
</p>

## مكتبة الرسائل القصيرة لمزود الخدمة صفا للرسائل القصيرة
## Laravel Safa sms Library https://www.safa-sms.com
This is a Laravel package to send SMS using https://www.safa-sms.com
## Installation

1. Install the package using Composer:
```
Composer require alkoumi/laravel-safa-sms
```

2. The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:

```
        ALkoumi\LaravelSafaSms\SafaSmsServiceProvider::class,
```

3. Publish 🥳 the configuration 💼 file using:

```
php artisan vendor:publish --provider='ALkoumi\LaravelSafaSms\SafaSmsServiceProvider'
```

4. In your `.env` file add your https://www.safa-sms.com login details

```
SAFA_SMS_USERNAME=username
SAFA_SMS_PASSWORD=password
SAFA_SMS_FORMALSENDER=formal-sender
SAFA_SMS_ADSSENDER=Ads-sender
```

## Usage [ as elegant as Laravel 💗]

```
 use Alkoumi\LaravelSafaSms\Facades\SafaSMS;

    $message = 'جعل الله ما قدمتكم نهرًا جاريًا من الحسنات';
    
    SafaSMS::text($message)                                     // { required } the test message to send
        ->to(User::all())                                       // { required } as Mixed|array|object|collection
        ->asFormal()                                            // { optional } the Ads-sender used by default unless you add ->asFormal() 
        ->removeDuplication()                                   // { optional } to remove the duplicated numbers
        ->send();                                               // { required } // in the end to send 🧐
```

1- For one recipient you must pass type `Mixed|Array` like `->to('0500175200')` or `->to(['0500175200'])`.

2- If you have `database` field `$model->mobile` then you can pass `Array|Object|Collection`.

3- For multiple recipients Just pass type `Array|Object|Collection` in `->to(User::all())`.

4- If you pass type of `Array|Object|Collection`, We will tack care 😎 of getting mobile numbers.

5- `removeDuplicate()` Will remove all duplicate numbers.

6- By default we'll user Ads-sender unless you use `->asFormal()` to use the Formal sender name from www.safa-sms.com.

7- Add `admin_email` in the configuration file `Config\safa-sms.php` to notify Admin the results in every request.

#### Give Me 💗 Cup of ☕️ Coffee here https://patreon.com/mohammadelkoumi

