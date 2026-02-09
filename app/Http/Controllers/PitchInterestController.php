<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePitchInterestRequest;
use App\Models\Pitch;
use App\Models\PitchInterest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PitchInterestController extends Controller
{
    /**
     * List interests on the current user's pitch.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        $pitch = $request->user()->pitch;

        if ($pitch === null) {
            return redirect()->route('pitches.create');
        }

        $interests = $pitch->interests()
            ->with(['user:id,name,email'])
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('pitches/Interests', [
            'pitch' => $pitch->only(['id', 'title', 'slug']),
            'interests' => $interests,
        ]);
    }

    /**
     * Store interest in a pitch (express interest).
     */
    public function store(StorePitchInterestRequest $request, Pitch $pitch): RedirectResponse
    {
        $this->authorize('createInterest', $pitch);

        $existing = PitchInterest::where('user_id', $request->user()->id)
            ->where('pitch_id', $pitch->id)
            ->first();

        if ($existing !== null) {
            return redirect()->route('pitches.show', $pitch)
                ->with('status', 'You have already expressed interest.');
        }

        PitchInterest::create([
            'user_id' => $request->user()->id,
            'pitch_id' => $pitch->id,
            'message' => $request->validated('message'),
            'status' => 'pending',
        ]);

        return redirect()->route('pitches.show', $pitch)
            ->with('status', 'Interest sent.');
    }

    /**
     * Update interest status (accept/decline) as pitch owner.
     */
    public function update(Request $request, PitchInterest $interest): RedirectResponse
    {
        $this->authorize('update', $interest);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'declined'])],
        ]);

        $interest->update($validated);

        return redirect()->route('pitches.interests.index')
            ->with('status', 'Interest updated.');
    }
}
