<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DiscoverController extends Controller
{
    /**
     * Browse published pitches with optional filters.
     */
    public function __invoke(Request $request): Response
    {
        $query = Pitch::query()
            ->where('status', 'published')
            ->with(['user:id,name', 'skills:id,name,slug']);

        if ($request->user()) {
            $query->where('user_id', '!=', $request->user()->id);
        }

        if ($request->filled('pitch_type')) {
            $query->where('pitch_type', $request->string('pitch_type'));
        }

        if ($request->filled('role_sought')) {
            $query->where('role_sought', 'like', '%'.$request->string('role_sought').'%');
        }

        if ($request->filled('skills') && is_array($request->input('skills'))) {
            $skillIds = array_filter(array_map('intval', $request->input('skills')));
            if ($skillIds !== []) {
                $query->whereHas('skills', function ($q) use ($skillIds): void {
                    $q->whereIn('skills.id', $skillIds);
                });
            }
        }

        $pitches = $query->orderByDesc('updated_at')->paginate(12)->withQueryString();

        return Inertia::render('discover/Index', [
            'pitches' => $pitches,
            'filters' => [
                'pitch_type' => $request->input('pitch_type'),
                'role_sought' => $request->input('role_sought'),
                'skills' => $request->input('skills', []),
            ],
            'skills' => Skill::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }
}
