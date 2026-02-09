<?php

use App\Models\Pitch;
use App\Models\Skill;
use App\Models\User;

test('authenticated user can create a pitch', function () {
    Skill::create(['name' => 'Laravel', 'slug' => 'laravel']);
    Skill::create(['name' => 'Vue.js', 'slug' => 'vue-js']);

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('pitches.store'), [
            'title' => 'My Awesome Project',
            'tagline' => 'Building the future of X',
            'body' => 'Full description here.',
            'pitch_type' => 'project',
            'role_sought' => 'Technical co-founder',
            'skill_ids' => [1, 2],
            'status' => 'draft',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('pitches.index'));

    $pitch = Pitch::where('user_id', $user->id)->first();

    expect($pitch)->not->toBeNull();
    expect($pitch->title)->toBe('My Awesome Project');
    expect($pitch->tagline)->toBe('Building the future of X');
    expect($pitch->status)->toBe('draft');
    expect($pitch->slug)->not->toBeEmpty();

    $pitch->load('skills');
    expect($pitch->skills->pluck('id')->sort()->values()->all())->toEqual([1, 2]);
});

test('authenticated user can update their pitch', function () {
    $user = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $user->id,
        'title' => 'Old Title',
        'slug' => 'old-title',
        'tagline' => 'Old tagline',
        'body' => 'Old body',
        'pitch_type' => 'project',
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch(route('pitches.update', $pitch), [
            'title' => 'Updated Title',
            'tagline' => 'Updated tagline',
            'body' => 'Updated body.',
            'pitch_type' => 'seeking',
            'role_sought' => null,
            'skill_ids' => [],
            'status' => 'published',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('pitches.index'));

    $pitch->refresh();

    expect($pitch->title)->toBe('Updated Title');
    expect($pitch->status)->toBe('published');
});

test('user cannot edit another user pitch', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $pitch = Pitch::create([
        'user_id' => $owner->id,
        'title' => 'Owner Pitch',
        'slug' => 'owner-pitch',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs($other)
        ->get(route('pitches.edit', $pitch));

    $response->assertForbidden();
});

test('discover page shows published pitches', function () {
    $user = User::factory()->create();
    Pitch::create([
        'user_id' => $user->id,
        'title' => 'Published Idea',
        'slug' => 'published-idea',
        'tagline' => 'A tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);

    $response = $this->get(route('discover'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('discover/Index')
        ->has('pitches')
        ->has('pitches.data')
        ->where('pitches.data.0.title', 'Published Idea')
    );
});

test('pitch show page displays published pitch', function () {
    $user = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $user->id,
        'title' => 'Public Pitch',
        'slug' => 'public-pitch',
        'tagline' => 'Tagline',
        'body' => 'Full body',
        'pitch_type' => 'project',
        'status' => 'published',
    ]);

    $response = $this->get(route('pitches.show', $pitch));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('pitches/Show')
        ->where('pitch.title', 'Public Pitch')
        ->where('pitch.slug', 'public-pitch')
    );
});

test('draft pitch show returns 403 for guest', function () {
    $user = User::factory()->create();
    $pitch = Pitch::create([
        'user_id' => $user->id,
        'title' => 'Draft Pitch',
        'slug' => 'draft-pitch',
        'tagline' => 'Tagline',
        'body' => 'Body',
        'pitch_type' => 'project',
        'status' => 'draft',
    ]);

    $response = $this->get(route('pitches.show', $pitch));

    $response->assertForbidden();
});
