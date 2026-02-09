<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with the current user's pitch summary.
     */
    public function __invoke(Request $request): Response
    {
        $pitch = $request->user()->pitch;

        if ($pitch !== null) {
            $pitch->load('skills');
        }

        return Inertia::render('Dashboard', [
            'pitch' => $pitch,
        ]);
    }
}
