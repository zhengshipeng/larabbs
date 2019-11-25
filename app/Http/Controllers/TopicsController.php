<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 话题列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request, User $user, Link $link)
	{
		$topics = Topic::withOrder($request->order)->with('user', 'category')->paginate();
		$active_users = $user->getActiveUsers();
		$links = $link->getAllCached();
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    /**
     * 话题详情页面
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * 发表话题页面
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * 创建话题
     * @param TopicRequest $request
     * @param Topic $topic
     * @return $this
     */
	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
		$topic->user_id = \Auth::id();
		$topic->save();
		//return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功!');
		return redirect()->to($topic->link())->with('success', '帖子创建成功!');
	}

    /**
     * 编辑话题
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * 更新话题
     * @param TopicRequest $request
     * @param Topic $topic
     * @return $this
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
        $topic->update($request->all());
		//return redirect()->route('topics.show', $topic->id)->with('success', '帖子修改成功！');
		return redirect()->to($topic->link())->with('success', '帖子修改成功！');
	}


    /**
     * @param Topic $topic
     * @return $this
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '帖子删除成功！');
	}


    /**
     * 话题图片上传
     * @param Request $request
     * @param ImageUploadHandler $imageUploadHandler
     * @return array
     */
	public function uploadImage(Request $request, ImageUploadHandler $imageUploadHandler)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];

        if ($file = $request->upload_file) {
            $result = $imageUploadHandler->save($file, 'topics', \Auth::id(), 1024);
            if ($result) {
                $data['success'] = true;
                $data['msg'] = '上传成功';
                $data['file_path'] = $result['path'];
            }
        }

        return $data;
    }




}