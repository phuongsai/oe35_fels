<?php

namespace Tests\Feature\views\front_end\courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;

class ListPageTest extends TestCase
{
    protected $response;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $url = route('fels.course.list');
        $this->response = $this->get($url);
    }

    public function test_it_list_page_view()
    {
        $response = $this->response;

        $response->assertSee(trans('messages.front_end.nav.courses'));
    }

    public function test_it_view_list_course()
    {
        $course = [];
        $limit = config('const.seeder.number');
        for ($i = 0; $i < $limit; $i++) {
            $course[$i] = factory(Course::class)->create();
        }

        $response = $this->response;
        for ($i = 0; $i < $limit; $i++) {
            $response->assertSee($course[$i]->name);
            $response->assertSee($course[$i]->description);
            $response->assertSee($course[$i]->users_count);
            $response->assertSee($course[$i]->words_count);
        }

        $response->assertDontSee(trans('messages.front_end.fels.not_found'));
    }

    public function test_it_can_navigate_detail_course_page()
    {
        $user = factory(User::class)->create([
            'role_id' => config('const.seeder.role_id'),
        ]);
        $course = factory(Course::class)->create();
        $response = $this->actingAs($user)->get(route('fels.course.detail', $course));

        $response->assertSee($course->name);
        $response->assertSee($course->description);
    }

    public function test_view_empty_course()
    {
        Course::query()->delete();

        $response = $this->response;
        $response->assertDontSee(trans('messages.front_end.fels.not_found'));
    }
}
