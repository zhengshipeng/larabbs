<?php

namespace App\Http\Controllers\Api;

use App\Http\Queries\TopicQuery;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicsController extends Controller
{
    /**
     * 帖子列表
     * @param Request $request
     * @param TopicQuery $topicQuery
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, TopicQuery $topicQuery)
    {
        /*$query = $topic->query();

        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        $topics = $query->with('user', 'category')->withOrder($request->order)->paginate();

        return TopicResource::collection($topics);*/
        $topics =$topicQuery->paginate();

        return TopicResource::collection($topics);
    }

    /**
     *  用户发布的帖子
     * @param Request $request
     * @param User $user
     * @param TopicQuery $topicQuery
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function userIndex(Request $request, User $user, TopicQuery $topicQuery)
    {
        $topics = $topicQuery->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    /**
     * 帖子详情
     * @param $topicId
     * @param TopicQuery $topicQuery
     * @return TopicResource
     */
    public function show($topicId, TopicQuery $topicQuery)
    {
        $topic = $topicQuery->findOrFail($topicId);
        return new TopicResource($topic);
    }

    /**
     * 发布话题
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     */
    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    /**
     * 修改话题
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());

        return new  TopicResource($topic);
    }

    /**
     * 删除话题
     * @param Topic $topic
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return response(null, 204);
    }
}
