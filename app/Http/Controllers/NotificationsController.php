<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 用户通知首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $notifications = \Auth::user()->notifications()->paginate(5);
        \Auth::user()->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
