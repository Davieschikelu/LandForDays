<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('landlord_id', Auth::id())
            ->where('is_archived', false)
            ->withCount(['units', 'units as vacant_units_count' => function ($query) {
                $query->where('status', 'vacant');
            }])
            ->get();

        return view('landlord.properties.index', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:house,apartment,commercial',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['landlord_id'] = Auth::id();

        Property::create($validated);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property created successfully!');
    }

    public function show(Property $property)
    {
        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->load('units');

        return view('landlord.properties.show', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:house,apartment,commercial',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $property->update($validated);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property)
    {
        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // We soft-archive instead of actual hard deletion
        $property->update(['is_archived' => true]);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property archived successfully!');
    }
}
