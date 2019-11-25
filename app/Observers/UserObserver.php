<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{

    public function saving(User $user)
    {
        if (empty($user->avatar)) {
            $user->avatar = config('app.url') . '/uploads/images/avatars/ktHsGiDchD925yMBc1jMPEWJY7QADKIl.jpg';
        }
    }

    public function deleted(User $user)
    {
        DB::table('topics')->where('user_id', $user->id)->delete();
        DB::table('replies')->where('user_id', $user->id)->delete();
    }


}