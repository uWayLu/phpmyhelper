<?php
namespace WebbLu;

class Helper
{
    public static function getNow($format = 'YmdHis', $timezone = 'Asia/Taipei')
    {
        $dateTime = new \DateTime('now', new \DateTimeZone($timezone));
        return $dateTime->format($format);
    }

    public static function getServerIp()
    {
        return $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
    }

    public static function getClientAddress()
    {
        return
        !empty($_SERVER['HTTP_CLIENT_IP'])
        ? $_SERVER['HTTP_CLIENT_IP'] : !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
        ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public static function getRandomStr($length = 2)
    {
        $str = '';
        $char = array_merge(range('a', 'z'), range('0', '9'));

        $max = count($char) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $char[$rand];
        }
        return $str;
    }

    public static function cleanXss(&$input, $low = false)
    {
        if (!is_array($input)) {
            $input = trim($input);
            $input = strip_tags($input);
            $input = htmlspecialchars($input);
            if ($low) {
                return true;
            }
            $input = str_replace(array('"', "\\", "'", "/", "..", "../", "./", "//"), '', $input);
            $no = '/%0[0-8bcef]/';
            $input = preg_replace($no, '', $input);
            $no = '/%1[0-9a-f]/';
            $input = preg_replace($no, '', $input);
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $input = preg_replace($no, '', $input);
            return true;
        }
        $keys = array_keys($input);
        foreach ($keys as $key) {
            self::cleanXss($input[$key]);
        }
    }
}
