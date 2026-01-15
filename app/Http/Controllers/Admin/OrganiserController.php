<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class OrganiserController extends Controller
{
    public function index()
    {
        $organisers = User::where('role', 'organiser')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.organisers.index', compact('organisers'));
    }

    public function create()
    {
        return view('admin.organisers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'organiser',
        ]);

        return redirect()->route('admin.organisers.index')
            ->with('success', 'Organiser account created successfully!');
    }

    public function edit(User $organiser)
    {
        if ($organiser->role !== 'organiser') {
            abort(404);
        }

        return view('admin.organisers.edit', compact('organiser'));
    }

    public function update(Request $request, User $organiser)
    {
        if ($organiser->role !== 'organiser') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $organiser->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $organiser->name = $validated['name'];
        $organiser->email = $validated['email'];

        if (!empty($validated['password'])) {
            $organiser->password = Hash::make($validated['password']);
        }

        $organiser->save();

        return redirect()->route('admin.organisers.index')
            ->with('success', 'Organiser account updated successfully!');
    }

    public function destroy(User $organiser)
    {
        if ($organiser->role !== 'organiser') {
            abort(404);
        }

        $organiser->delete();

        return redirect()->route('admin.organisers.index')
            ->with('success', 'Organiser account deleted successfully!');
    }

    public function viewDashboard(User $organiser)
    {
        $events = Event::where('user_id', $organiser->id)
            ->withCount('pesertas')
            ->with(['categories', 'customCategories'])
            ->latest()
            ->get();

        $participantSummary = $events->map(function ($event) {
            return [
                'event_id' => $event->id,
                'title' => $event->title,
                'event_type' => $event->event_type,
                'total' => $event->pesertas_count
            ];
        });

        return view('admin.organisers.dashboard', compact('organiser', 'events', 'participantSummary'));
    }
}
