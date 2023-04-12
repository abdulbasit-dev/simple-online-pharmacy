<?php

use Carbon\Carbon;

if (!function_exists("singular")) {
    //make title singular
    function singular(string $title)
    {
        return substr($title, 0, -1);
    }
}

if (!function_exists("getFile")) {
    function getFile($model, $collection = "default")
    {
        return $model->getFirstMediaUrl($collection) ? URL::asset($model->getFirstMediaUrl($collection)) : URL::asset('assets/images/placeholder.png');
    }
}

if (!function_exists("getFileSize")) {
    function getFileSize($model)
    {
        // get file size
        return $model->getFirstMedia() ? $model->getFirstMedia()->size : 0;
    }
}

if (!function_exists("getAvatar")) {
    function getAvatar($user)
    {
        return $user->getFirstMedia() ? asset($user->getFirstMedia()->getUrl()) : 'https://ui-avatars.com/api/?background=random&name=' . $user->name;
    }
}


if (!function_exists("generateRandomString")) {
    function generateRandomString(int $lengh = 16)
    {
        // generate random string with length 32
        return strtoupper(bin2hex(random_bytes($lengh)));
    }
}

if (!function_exists("systemCurrency")) {
    function systemCurrency()
    {

        return "SEK";
    }
}

//return error message with file name and line number
if (!function_exists("showErrorMessage")) {
    function showErrorMessage($e)
    {
        // check env if its not in production, then show full message
        if (config('app.env') != 'production') {
            return $e->getMessage() . " in " . $e->getFile() . " at line " . $e->getLine();
        } else {
            return $e->getMessage();
        }
    }
}

//convert snake case to title case order_data => Order Data
if (!function_exists("snakeToTitle")) {
    function snakeToTitle(string $string = '')
    {
        return ucwords(str_replace("_", " ", $string));
    }
}

if (!function_exists("getDayDiff")) {
    function getDayDiff($data)
    {
        $expire_at = Carbon::parse($data);
        $now = Carbon::now();
        $diff = $now->diffInDays($expire_at, false);
        return $diff;
    }
}

if (!function_exists("formatPrice")) {
    function formatPrice($price)
    {
        return number_format($price, 0, '') . ' IQD';
    }
}

if (!function_exists("formatDate")) {
    function formatDate($date)
    {
        return Carbon::parse($date)->format("M d, Y");
    }
}

if (!function_exists("formatDateWithTimezone")) {
    function formatDateWithTimezone($date)
    {
        return $date ? Carbon::parse($date)->format("M d, Y - h:i A") : "---";
    }
}

if (!function_exists("expireClass")) {
    function expireClass($date)
    {
        $class = "";
        if ($date <  Carbon::now()) {
            //expired
            $class = "badge badge-pill badge-soft-danger font-size-13";
        } else if (Carbon::today()->eq(Carbon::parse($date)->startOfDay())) {
            //today
            $class = "badge badge-pill badge-soft-info font-size-13 ";
        } else {
            //upcoming
            $class = "";
        }
        return $class;
    }
}
