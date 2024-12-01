<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class WithdrawMethod extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name_bank',
        'name_account',
        'number_account',
        'branch',
        'min_withdraw',
        'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
     
    public static function getEnumValues($field)
    {
        $table = (new static)->getTable(); // Lấy tên bảng từ model
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = ?", [$field]);

        if (!empty($type)) {
            preg_match('/^enum\((.*)\)$/', $type[0]->Type, $matches);
            $enum = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));

            return $enum;
        }

        return [];
    }

}
