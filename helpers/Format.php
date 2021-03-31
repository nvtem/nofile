<?
namespace app\helpers;

class Format {
    public static function format_bytes($size, $precision = 2) {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    public static function trunc_string_add_ellipsis($s, $max_length) {
        if (strlen($s) > $max_length)
            return substr($s, 0, $max_length-1) . '&#8230;';
        else
            return $s;
    }
}