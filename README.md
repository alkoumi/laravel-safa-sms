<p align="center">

[![License](https://poser.pugx.org/alkoumi/laravel-safa-sms/license)](//packagist.org/packages/alkoumi/laravel-safa-sms)
[![Latest Stable Version](https://poser.pugx.org/alkoumi/laravel-safa-sms/v)](//packagist.org/packages/alkoumi/laravel-safa-sms)
[![Total Downloads](https://poser.pugx.org/alkoumi/laravel-safa-sms/downloads)](//packagist.org/packages/alkoumi/laravel-safa-sms)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/alkoumi/laravel-safa-sms)
![Packagist Version](https://img.shields.io/packagist/v/alkoumi/laravel-safa-sms?color=red)
![Packagist Stars](https://img.shields.io/packagist/stars/alkoumi/laravel-safa-sms)

</p>

## مكتبة الرسائل القصيرة لمزود الخدمة صفا للرسائل القصيرة
## Laravel Safa sms Library https://www.safa-sms.com
This is a Laravel package to send SMS using https://www.safa-sms.com
## Installation

1. Install the package using Composer:
```
Composer require alkoumi/laravel-safa-sms
```

2. The service provider will automatically registered. Or you may manually do in your `config/app.php` file:

```
'providers' => [
      // ...
      ALkoumi\LaravelSafaSms\SafaSmsServiceProvider::class,
];
```

3. Publish 🥳 the configuration 💼 file using:

```
php artisan vendor:publish --provider='ALkoumi\LaravelSafaSms\SafaSmsServiceProvider'
```

4. In your `.env` file add your https://www.safa-sms.com login details like: 

```
SAFA_SMS_USERNAME=username
SAFA_SMS_PASSWORD=password
SAFA_SMS_FORMALSENDER=formal-sender
SAFA_SMS_ADSSENDER=Ads-sender
```

## Usage [ as elegant as Laravel 💗]
![Shamel](imags/safa-sms.png)
```
 use Alkoumi\LaravelSafaSms\Facades\SafaSMS;

    $message = 'جعل الله ما قدمتكم نهرًا جاريًا من الحسنات';
    
    SafaSMS::text($message)             // { required } the test message to send
        ->to(User::all())               // { required } as Mixed|array|object|collection
        ->asFormal()                    // { optional } the Ads-sender used by default unless you add ->asFormal() 
        ->removeDuplication()           // { optional } to remove the duplicated numbers
        ->send();                       // { required } at the end to send 🧐
```
## Usage in `.blade.php` files get your Balance 😉
```
    {{ SafaSMS::getBalance() }}
```


1- For one recipient, You must pass types `Mixed|Array` like `->to('0500175200')` or `->to(['0500175200'])`.

2- If you have `database` field `$model->mobile` then you can pass types `Array|Object|Collection`.

3- For multiple recipients, Just pass types `Array|Object|Collection` in `->to(User::all())`.

4- If you pass types of `Array|Object|Collection`, We will tack care 😎 of getting mobile numbers.

5- If you want to remove all duplicate numbers, Just get `removeDuplicate()`.

6- By default Ads sender name will used, unless you add `->asFormal()` to use formal sender name from safa-sms.

7- Add `admin_email` in the `Config\safa-sms.php` to notify Admin with results in every request.

#### Give Me 💗 Cup of ☕️ Coffee here https://patreon.com/mohammadelkoumi

