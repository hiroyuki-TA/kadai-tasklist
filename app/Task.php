<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model

{
    /**$fillsble create で一気に保存可能することを許可する
     * 通常は、すべてのカラムを「一気に保存不可」であるため
     *
    */
     protected $fillable = ['content','status','user_id'];
     
     /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
