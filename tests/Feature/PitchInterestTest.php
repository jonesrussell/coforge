<?php

use App\Models\Pitch;
use App\Models\PitchInterest;
use App\Models\User;

test('authenticated user can express interest in a published pitch', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $owner->id,
        'title' => 'A Project',
        'slug' => 'a-project',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);

    $response = $this
        ->actingAs($viewer)
        ->post(route('pitches.interest.store', $pitch), [
            'message' => 'I would like to connect.',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('pitches.show', $pitch));

    $interest = PitchInterest::where('pitch_id', $pitch->id)
        ->where('user_id', $viewer->id)
        ->first();

    expect($interest)->not->toBeNull();
    expect($interest->status)->toBe('pending');
    expect($interest->message)->toBe('I would like to connect.');
});

test('user cannot express interest in their own pitch', function () {
    $user = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $user->id,
        'title' => 'My Pitch',
        'slug' => 'my-pitch',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('pitches.interest.store', $pitch), ['message' => 'Hi']);

    $response->assertForbidden();
});

test('pitch owner can view interests and accept one', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $owner->id,
        'title' => 'A Project',
        'slug' => 'a-project',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);
    $interest = PitchInterest::create([
        'user_id' => $other->id,
        'pitch_id' => $pitch->id,
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($owner)
        ->get(route('pitches.interests.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('pitches/Interests')
        ->has('interests')
        ->where('interests.0.id', $interest->id)
    );

    $updateResponse = $this
        ->actingAs($owner)
        ->patch(route('pitches.interests.update', $interest), [
            'status' => 'accepted',
        ]);

    $updateResponse
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('pitches.interests.index'));

    $interest->refresh();
    expect($interest->status)->toBe('accepted');
});

test('non-owner cannot update interest status', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $owner->id,
        'title' => 'A Project',
        'slug' => 'a-project',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);
    $interest = PitchInterest::create([
        'user_id' => $other->id,
        'pitch_id' => $pitch->id,
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($other)
        ->patch(route('pitches.interests.update', $interest), [
            'status' => 'accepted',
        ]);

    $response->assertForbidden();
});
