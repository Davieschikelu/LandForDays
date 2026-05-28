<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0|max:100',
            'status' => 'required|string|in:vacant,occupied,maintenance',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        Unit::create($validated);

        return redirect()->route('landlord.properties.show', $property->id)
            ->with('success', 'Unit added successfully!');
    }

    public function update(Request $request, Unit $unit)
    {
        $property = $unit->property;

        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'unit_number' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0|max:100',
            'status' => 'required|string|in:vacant,occupied,maintenance',
        ]);

        $unit->update($validated);

        return redirect()->route('landlord.properties.show', $property->id)
            ->with('success', 'Unit updated successfully!');
    }

    public function destroy(Unit $unit)
    {
        $property = $unit->property;

        if ($property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $unit->delete();

        return redirect()->route('landlord.properties.show', $property->id)
            ->with('success', 'Unit deleted successfully!');
    }
}
