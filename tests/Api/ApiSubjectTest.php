<?php

namespace Tests\Api;

use Tests\TestCase;
use App\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSubjectTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function an_unauthificated_client_can_not_get_subjects() 
    {
        $this->get('/api/v1/subjects')->assertStatus(302);
        $this->contains('login');
    }

    /** @test 
     * Use Route: GET, /api/v1/subjects
     */
    public function an_authificated_client_can_get_all_subjects() 
    {

        $this->signInApiAdmin();

        $this->get('/api/v1/subjects')
                ->assertStatus(200)
                ->assertJson(Subject::all()->toArray());
    }

    /** @test 
     * Use Route: GET, /api/v1/subjects/{id}
     */
    public function an_authificated_client_can_get_a_subject() 
    {
        $this->signInApiAdmin();

        $this->get('/api/v1/subjects/59')
                ->assertStatus(200)
                ->assertJson(Subject::find(59)->toArray());
    }

}
