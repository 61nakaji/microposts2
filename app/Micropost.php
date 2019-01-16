<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        


    public function favorited()
    {
        return $this->belongsToMany(User::class, 'user_favorite', 'favorite_id', 'user_id')->withTimestamps();
    }

    public function favorite($micropostId)
    {
        // 既にfavしているかの確認
        $exist = $this->is_favoriting($micropostId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $micropostId;

        if ($exist || $its_me) {
            // 既にfavしていれば何もしない
            return false;
        } else {
            // 未favであればfavする
            $this->favoritings()->attach($micropostId);
            return true;
        }
    }

    public function unfavorite($micropostId)
    {
        // 既にfavしているかの確認
        $exist = $this->is_favoriting($micropostId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $micropostId;

        if ($exist && !$its_me) {
            // 既にfavしていればfavを外す
            $this->favoritings()->detach($micropostId);
            return true;
        } else {
            // 未favであれば何もしない
            return false;
        }
    }

    public function is_favoriting($micropostId)
    {
        return $this->favoritings()->where('favorite_id', $micropostId)->exists();
    }


}
