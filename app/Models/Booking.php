<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','start_time','end_time','user_id','client_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(client::class);
    }

    // detect Overlap bookings
    public static function hasOverlap($userId, $start, $end, $ignored=null){
        return self::where('user_id', $userId)->
            where(function($query) use($start, $end){
            $query->whereBetween('start_time',[$start,$end])->
                orWhereBetween('end_time',[$start,$end])->
                orWhere(function ($q) use($start,$end){
                    $q-where('start_time','<=',$start)->
                    where('end_time','>=',$end);
            });
        })->when($ignored, fn($q) => $q->where('id', '!=', $ignored) )->
            exists();
    }

}
