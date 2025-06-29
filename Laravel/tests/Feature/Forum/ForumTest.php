<?php

namespace Tests\Feature\Forum;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\ForumDiscussion;
use App\Models\ForumReply;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Manager $manager;

    private Employee $employee;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager  = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->customer = Customer::factory()->create();
    }

    public function test_manager_can_access_forum()
    {
        $this->actingAs($this->manager, 'manager');
        $response = $this->get(route('forum.index'));

        $response->assertViewIs('forum.index');
    }

    public function test_employee_can_access_forum()
    {
        $this->actingAs($this->employee, 'employee');
        $response = $this->get(route('forum.index'));

        $response->assertViewIs('forum.index');
    }

    public function test_customer_can_access_forum()
    {
        $this->actingAs($this->customer, 'customer');
        $response = $this->get(route('forum.index'));

        $response->assertViewIs('forum.index');
    }

    public function test_guest_cannot_access_forum()
    {
        $response = $this->get(route('forum.index'));

        $response->assertRedirect(route('customer.login'));
    }

    public function test_customer_can_start_a_discussion()
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->post(route('forum.discussion.store'), [
            'title'   => 'Test Discussion',
            'content' => 'This is a test discussion content.',
        ]);

        $response->assertRedirect(route('forum.index'));
        $response->assertSessionHas('success', __('forum/discussion.created'));

        $this->assertDatabaseHas('forum_discussions', [
            'title'       => 'Test Discussion',
            'content'     => 'This is a test discussion content.',
            'customer_id' => $this->customer->id,
        ]);
    }

    public function test_customer_can_edit_a_discussion()
    {
        $this->actingAs($this->customer, 'customer');

        $discussion = ForumDiscussion::factory()->create([
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->put(route('forum.discussion.update', $discussion), [
            'title'   => 'Updated Title',
            'content' => 'Updated content.',
        ]);

        $response->assertRedirect(route('forum.discussion.show', $discussion));
        $response->assertSessionHas('success', __('forum/discussion.updated'));

        $this->assertDatabaseHas('forum_discussions', [
            'title'   => 'Updated Title',
            'content' => 'Updated content.',
        ]);
    }

    public function test_customer_can_reply_to_a_discussion()
    {
        $this->actingAs($this->customer, 'customer');

        $discussion = ForumDiscussion::factory()->create();

        $response = $this->post(route('forum.discussion.replies.store', $discussion), [
            'discussion_id' => $discussion->id,
            'content'       => 'This is a reply to the discussion.',
            'customer_id'   => $this->customer->id,
        ]);

        $response->assertRedirect(route('forum.discussion.show', $discussion));

        $this->assertDatabaseHas('forum_replies', [
            'discussion_id' => $discussion->id,
            'content'       => 'This is a reply to the discussion.',
            'user_id'       => $this->customer->id,
        ]);
    }

    public function test_customer_can_edit_a_reply_to_a_discussion()
    {
        $this->actingAs($this->customer, 'customer');

        $discussion = ForumDiscussion::factory()->create();

        $reply = ForumReply::factory()->create([
            'discussion_id' => $discussion->id,
            'customer_id'   => $this->customer->id,
        ]);

        $response = $this->put(route('forum.discussion.replies.update', $reply), [
            'content' => 'This is a updated reply to the discussion.',
        ]);

        $response->assertRedirect(route('forum.discussion.show', $discussion));

        $this->assertDatabaseHas('forum_replies', [
            'discussion_id' => $discussion->id,
            'content'       => 'This is a updated reply to the discussion.',
            'user_id'       => $this->customer->id,
        ]);
    }

    public function test_customer_can_delete_a_reply()
    {
        $this->actingAs($this->customer, 'customer');

        $discussion = ForumDiscussion::factory()->create();

        $reply = ForumReply::factory()->create([
            'discussion_id' => $discussion->id,
            'user_id'       => $this->customer->id,
        ]);

        $response = $this->delete(route('forum.discussion.replies.destroy', $reply));

        $response->assertRedirect(route('forum.discussion.show', $discussion));
    }

    public function test_employee_can_reply_to_a_discussion()
    {
        $this->actingAs($this->employee, 'employee');

        $discussion = ForumDiscussion::factory()->create();

        $response = $this->post(route('forum.discussion.replies.store', $discussion), [
            'discussion_id' => $discussion->id,
            'content'       => 'This is a reply from an employee.',
            'user_id'       => $this->employee->id,
        ]);

        $response->assertRedirect(route('forum.discussion.show', $discussion));

        $this->assertDatabaseHas('forum_replies', [
            'discussion_id' => $discussion->id,
            'content'       => 'This is a reply from an employee.',
            'user_id'       => $this->employee->id,
        ]);
    }

    public function test_employee_can_edit_a_reply_to_a_discussion()
    {
        $this->actingAs($this->employee, 'employee');

        $discussion = ForumDiscussion::factory()->create();

        $reply = ForumReply::factory()->create([
            'discussion_id' => $discussion->id,
            'employee_id'   => $this->employee->id,
        ]);

        $response = $this->put(route('forum.discussion.replies.update', $reply), [
            'content' => 'This is an updated reply from an employee.',
        ]);

        $response->assertRedirect(route('forum.discussion.show', $discussion));

        $this->assertDatabaseHas('forum_replies', [
            'discussion_id' => $discussion->id,
            'content'       => 'This is an updated reply from an employee.',
            'user_id'       => $this->employee->id,
        ]);
    }

    public function test_employee_can_delete_a_reply()
    {
        $this->actingAs($this->employee, 'employee');

        $discussion = ForumDiscussion::factory()->create();

        $reply = ForumReply::factory()->create([
            'discussion_id' => $discussion->id,
            'employee_id'   => $this->employee->id,
        ]);

        $response = $this->delete(route('forum.discussion.replies.destroy', $reply));

        $response->assertRedirect(route('forum.discussion.show', $discussion));
    }
}
