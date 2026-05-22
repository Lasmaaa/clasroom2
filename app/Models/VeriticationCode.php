<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VerificationCode extends Model
{
    protected $fillable = ['user_id', 'type', 'code', 'is_used'];

    public static function generateUniqueCode(string $type): string
    {
        do {
            if ($type === 'numeric') {
                $code = (string) random_int(100000, 999999);
            } else {
                $code = Str::upper(Str::random(16));
            }

            $exists = self::where('code', $code)->exists();
        } while ($exists);

        return $code;
    }
}