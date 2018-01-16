<?php

namespace App\Observers;

use App\User;
use Hashids;

class UserObserver
{
    public function created(User $user)
    {
        $user->hashid = Hashids::connection('user')->encode($user->id);
        $user->save();
    }
}