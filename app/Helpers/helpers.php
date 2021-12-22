<?php
use Illuminate\Support\Facades\Auth;

if (!function_exists('on_page')) {
    function on_page($path)
    {
        return request()->is(LaravelLocalization::setLocale() . '/'. $path);
    }
}

if (!function_exists('return_if')) {
    function return_if($condition, $value)
    {
        if ($condition) {
            return $value;
        }
    }
}

if (! function_exists('fontDetect')) {
    function fontDetect(string $content, $default = "zawgyi")
    {
        return SteveNay\MyanFont\MyanFont::fontDetect($content, $default);
    }
}

if (! function_exists('isMyanmarSar')) {
    function isMyanmarSar(string $content)
    {
        return SteveNay\MyanFont\MyanFont::isMyanmarSar($content);
    }
}

if (! function_exists('uni2zg')) {
    function uni2zg(string $content)
    {
        return SteveNay\MyanFont\MyanFont::uni2zg($content);
    }
}

if (! function_exists('zg2uni')) {
    function zg2uni(string $content)
    {
        return SteveNay\MyanFont\MyanFont::zg2uni($content);
    }
}

if (! function_exists('currentUserType')) {
    function currentUserType()
    {
        if (!$user_type = optional(auth()->user())->type) {
            $user_type = App\User::TYPE_GUEST;
        }

        return $user_type;
    }
}

if (! function_exists('profileUrl')) {
    function profileUrl($user, $css = null)
    {
        if ($user) {
            return '<a class="'. $css. '" href="'.route('profile.show', $user->username).'">'.$user->name.'</a>';
        }
    }
}

if (! function_exists('mm_search_string')) {
    function mm_search_string($content, $default = "zawgyi")
    {
        if (!is_null($content)) {
            // $font = strtolower(fontDetect($content));
            $locale = app()->getLocale();

            if ($locale == 'my-ZG') {
                $content = zg2uni($content);
            }
        }

        return $content;
    }
}

if (! function_exists('format_like_query')) {
    function format_like_query($string)
    {
        if (config('cms.search_operator') == 'RLIKE') {
            $rule = '[[:<:]]%s[[:>:]]';
            $value = sprintf($rule, $string);
        } else {
            // CREDITS: https://tommcfarlin.com/sprintf-and-like-in-sql/
            $rule = '%%%s%%';
            $value = sprintf($rule, $string);
        }

        return $value;
    }
}

if (! function_exists('get_lecture_from_query_string_or_resource')) {
    function get_lecture_from_query_string_or_resource($from_resource, $from_query_string)
    {
        if ($from_resource) {
            return $from_resource;
        } else if($from_query_string) {
            return $from_query_string;
        }

        return '';
        
    }
}
