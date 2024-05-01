<?php

namespace App\Helpers;

class Helper
{
    public static function convertToSlug($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public static function getFormattedDate($date)
    {
        return date('F j, Y', strtotime($date));
    }

    public static function generateCode($length = 6) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $code = '';
      for ($i = 0; $i < $length; $i++) {
          $position = rand(0, strlen($characters) - 1);
          $code .= $characters[$position];
      }
      return $code;
    }
}
