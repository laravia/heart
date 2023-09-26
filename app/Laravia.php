<?php

namespace Laravia\Heart\App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravia\Heart\App\Classes\Composer;
use Laravia\Heart\App\Classes\Form;
use Laravia\Heart\App\Classes\Path;
use Laravia\Heart\App\Models\Model;

class Laravia
{

    public static function getTransKey($key = "", $externalPackageName = ""): string
    {
        $divider = '_';
        $key = explode($divider, $key);
        $keyElement = [];
        foreach ($key as $keyEntry) {
            if ($keyEntry != $divider) {
                $keyElement[] = ucfirst($keyEntry);
            }
        }
        $key = implode('', $keyElement);

        $divider = '.';
        $key = explode($divider, $key);
        $keyElement = [];
        foreach ($key as $keyEntry) {
            if ($keyEntry != $divider) {
                $keyElement[] = lcfirst($keyEntry);
            }
        }
        $key = implode('.', $keyElement);

        $package = 'laravia';
        if($externalPackageName) {
            $package = $externalPackageName;
        }

        $key = $package.'::' . 'common' . ($key ? '.' . $key : '');

        return $key;
    }

    public static function trans($key = "", $externalPackageName = ""): string
    {
        $key = self::getTransKey($key, $externalPackageName);
        return trans($key);
    }

    public static function path()
    {
        return new Path;
    }

    public static function model()
    {
        return new Model();
    }

    public static function config($key = ''): string|array|null
    {
        $composer = new Composer;
        $config = $composer->loadArrayFromPackageFileByKey('config');
        if ($key) {
            $config = data_get($config, $key);
        }

        return $config;
    }

    public static function info(): string
    {
        return Laravia::config('heart.name') . " " . Laravia::config('heart.version');
    }

    public static function commands($package = "heart"): array
    {
        return data_get(Laravia::config($package), 'commands', []);
    }

    public static function dd($var_or_object_or_array, $output = '')
    {
        if ($output == 'dd' || empty($output)) {
            dd($var_or_object_or_array);
        }

        if ($output == 1) {
            echo "<pre>";
            print_r($var_or_object_or_array);
            echo "</pre>";
        }
    }

    public static function form()
    {
        return new Form;
    }

    public static function uuid(): string
    {
        $data = random_bytes(16);
        assert(strlen($data) == 16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    public static function getOrderdConfig()
    {
        return collect(data_get(Laravia::config(), '*'))
            ->sortBy('sort')
            ->toArray();
    }


    public static function links(): array
    {
        $links = [];
        foreach (data_get(self::getOrderdConfig(), '*.links') as $linkData) {

            if ($linkData == null) {
                continue;
            }

            foreach ($linkData as $link) {
                $links[] = $link;
            }
        }
        return $links;
    }


    public static function getProjectNameFromDomain($url = "")
    {
        if (!$url) {
            $url = request()->getHost();
        }
        $urlParts = explode(".", $url);

        if (sizeof($urlParts) == 1) {
            return $urlParts[0];
        }
        if (sizeof($urlParts) == 2) {
            return $urlParts[0];
        }
        if (sizeof($urlParts) > 2) {
            return $urlParts[1];
        }
    }

    public static function getDomainNameWithoutSuburl($url = "")
    {
        if (!$url) {
            $url = request()->getHost();
        }
        $urlParts = explode(".", $url);
        if (sizeof($urlParts) == 1) {
            return $urlParts[0];
        }
        return $urlParts[count($urlParts) - 2] . "." . $urlParts[count($urlParts) - 1];
    }

    public static function getCssPageName()
    {
        $uri = Route::current()->uri;
        if ($uri == "/") {
            $uri = "";
        }
        return "site" . ($uri ? "_" . $uri : '_start');
    }

    public static function sendEmail(string $subject, string $body)
    {
        return Mail::html($body, function ($message) use ($subject) {
            $message->to(Laravia::config('heart.emailRecipient'))
                ->subject($subject)
                ->from(Laravia::config('heart.emailSender'));
        });
    }
}
