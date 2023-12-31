<?php

namespace Laravia\Heart\App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravia\Heart\App\Classes\Composer;
use Laravia\Heart\App\Classes\Path;
use Laravia\Heart\App\Models\Model;
use Orchid\Platform\Models\User;

class Laravia
{

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
        $config = $composer->includeFileFromPackageByKeyAndLoadContentIntoArray('config');
        if ($key) {
            $config = data_get($config, $key);
        }
        return $config;
    }

    public static function info(): string
    {
        return Laravia::config('heart.name') . " " . Laravia::config('heart.version');
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
        return self::getDataFromConfigByKey('links');
    }

    public static function commands(): array
    {
        return self::getDataFromConfigByKey('commands');
    }

    public static function schedules(): array
    {
        return self::getDataFromConfigByKey('schedules');
    }

    public static function dashboardMetrics(): array
    {
        return self::getDataFromConfigByKey('dashboard.metrics');
    }

    public static function getDataFromConfigByKey($key): array
    {
        $array = [];
        foreach (data_get(self::getOrderdConfig(), '*.' . $key) as $data) {

            if ($data == null) {
                continue;
            }

            foreach ($data as $key => $item) {
                if (is_string($key)) {
                    $array[$key] = $item;
                } else {
                    $array[] = $item;
                }
            }
        }
        return collect($array)->sortBy('sort')->toArray();
    }

    public static function getProjectNameFromDomain($url = "")
    {
        if (config('app.env') == "local" && !empty(env('APP_LOCAL_PROJECT'))) {
            return env('APP_LOCAL_PROJECT');
        } else {
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

    public static function sendEmail(string $subject, string $body, string $email = null)
    {
        return Mail::html($body, function ($message) use ($subject, $email) {
            if (!$email) {
                $email = env('MAIL_DEFAULT_RECIPIENT');
            }
            $message->to($email)
                ->subject($subject)
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }

    public static function isInitialBackendCall()
    {
        return  preg_match('/login/', request()->headers->get('referer')) &&
            preg_match('/\\' . config('platform.prefix') . '\/dashboard/', request()->url());
    }

    public static function isInitialCall($what)
    {
        if ($what == "backend") {
            return self::isInitialBackendCall();
        }
        return false;
    }

    public static function getAllPackageNames()
    {
        $composer = new Composer;
        return $composer->getAllPackageNames();
    }

    public static function isNewEntry(): bool
    {
        return !request()->get('id') ? true : false;
    }

    public static function getDashboardMetrics($what)
    {
        try {
            switch ($what) {
                case 'users':
                    return User::count();
                    break;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getArrayWithDistinctFieldDataFromClassByKey(string $class, string $field): array
    {
        return $class::select($field)->distinct()->get()->pluck($field, $field)->toArray();
    }
}
