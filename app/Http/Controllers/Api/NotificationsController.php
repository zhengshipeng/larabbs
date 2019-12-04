<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * 消息通知列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();
        return NotificationResource::collection($notifications);
    }

    /**
     * 消息统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        return response()->json([
            'unread_count' => $request->user()->notification_count,
        ]);
    }

    /**
     * 消息通知标记为已读
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $request->user()->markAsRead();
        return response(null, 204);
    }
}
