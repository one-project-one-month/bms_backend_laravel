<?php


namespace App\Traits;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Str;

trait GenerateCodeNumber
{
    
    protected static function generateUniqueCode(string $prefix)
    {
        $code = self::generateCode($prefix);
        while ( Admin::where('adminCode',$code)->exists()) {
            $code = self::generateCode($prefix);
        }
        return $code;
    }

    protected static function generateCode($prefix, $length = 9)
    {
       
        $randomString = strtoupper(Str::random($length - strlen($prefix)));
        return $prefix . $randomString;
    } 
}


?>