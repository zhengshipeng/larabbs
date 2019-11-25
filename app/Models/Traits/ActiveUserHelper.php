<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelper
{
    protected $users = [];

    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 7;
    protected $user_number = 6;

    protected $cache_key = "larabbs_active_users";
    protected $cache_expire_in_seconds = 65 * 60;

    public function getActiveUsers()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $active_users = $this->calculateActiveUsers();
        $this->cacheACtiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        $this->calculateReplyScore();
        $this->calculateTopicScore();
        $users  = \Illuminate\Support\Arr::sort($this->users, function ($user) {
            return $user['score'];
        });

        $users = array_reverse($users, true);

        $users = array_splice($users, 0, $this->user_number, true);
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            $user = $this->find($user_id);
            if ($user) {
                $active_users->push($user);
            }
        }
        return $active_users;
    }

    private function calculateTopicScore()
    {
        $topic_users = \App\Models\Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
                                                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays($this->pass_days))
                                                ->groupBy('user_id')
                                                ->get();
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore()
    {
        $reply_users = \App\Models\Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays($this->pass_days))
                                                ->groupBy('user_id')
                                                ->get();
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }


    private function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_seconds);
    }
}