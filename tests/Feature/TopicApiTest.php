<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];
        $response = $this->JWTActingAs($this->user)->json('POST', '/api/v1/topics/', $data);
        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];
        $response->assertStatus(201)->assertJsonFragment($assertData);
    }

    public function testUpdateTopic()
    {
        $topic = $this->makeTopic();
        $data = ['category_id' => 2, 'body' => 'edit body', 'title' => 'edit title'];
        $response = $this->JWTActingAs($this->user)->json('PATCH', '/api/v1/topics/' . $topic->id, $data);
        $assertData = [
            'category_id' => 2,
            'user_id' => $this->user->id,
            'title' => 'edit title',
            'body' => clean('edit body', 'user_topic_body'),
        ];
        $response->assertStatus(200)->assertJsonFragment($assertData);
    }

    public function makeTopic()
    {
        return factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => 1
        ]);
    }


}
