<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePitchRequest;
use App\Http\Requests\UpdatePitchRequest;
use App\Models\Pitch;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PitchController extends Controller
{
    /**
     * List the current user's pitch; redirect to create if none.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        $pitch = $request->user()->pitch;

        if ($pitch === null) {
            return redirect()->route('pitches.create');
        }

        $pitch->load('skills');

        return Inertia::render('pitches/Index', [
            'pitch' => $pitch,
        ]);
    }

    /**
     * Show the form for creating a pitch.
     */
    public function create(): Response
    {
        $this->authorize('create', Pitch::class);

        $pitch = new Pitch([
            'pitch_type' => 'project',
            'status' => 'draft',
        ]);

        return Inertia::render('pitches/Create', [
            'pitch' => $pitch,
            'skills' => Skill::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    /**
     * Store a newly created pitch.
     */
    public function store(StorePitchRequest $request): RedirectResponse
    {
        $this->authorize('create', Pitch::class);

        if ($request->user()->pitch !== null) {
            return redirect()->route('pitches.index')
                ->with('error', 'You already have a pitch.');
        }

        $validated = $request->validated();
        $skillIds = $validated['skill_ids'] ?? [];
        unset($validated['skill_ids']);
        $validated['user_id'] = $request->user()->id;

        $pitch = Pitch::create($validated);
        $pitch->skills()->sync($skillIds);

        return redirect()->route('pitches.index')
            ->with('status', 'Pitch created.');
    }

    /**
     * Show the form for editing the pitch.
     */
    public function edit(Request $request, Pitch $pitch): Response
    {
        $this->authorize('update', $pitch);

        $pitch->load('skills');

        return Inertia::render('pitches/Edit', [
            'pitch' => $pitch,
            'skills' => Skill::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    /**
     * Update the pitch.
     */
    public function update(UpdatePitchRequest $request, Pitch $pitch): RedirectResponse
    {
        $this->authorize('update', $pitch);

        $validated = $request->validated();
        $skillIds = $validated['skill_ids'] ?? [];
        unset($validated['skill_ids']);

        $pitch->update($validated);
        $pitch->skills()->sync($skillIds);

        return redirect()->route('pitches.index')
            ->with('status', 'Pitch updated.');
    }
}
