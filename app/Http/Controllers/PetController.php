<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pet;
use Exception;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function index()
    {

        $pets = Pet::all();
        return view('pages.pets.index', compact('pets'));
    }

    public function create()
    {

        return view('pages.pets.create');
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'age' => 'required|integer|min:0|max:50',
                'breed' => 'required|string|max:100',
                'allergies' => 'nullable|string',
                'description' => 'nullable|string',
                'profile' => 'nullable|image|max:51200',
                'sex' => 'required',
                'species' => 'required',
                'vaccination' => 'required',
                'spayed_neutered' => 'required',
                'status' => 'sometimes|in:available,pending,adopted'
            ]);
        } catch (Exception $e) {
            dd($e);
        }



        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'available'; // or 'pending'
        }

        // Generate filename using the SUBMITTED name (not DB)
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $cleanPetName = Str::slug($validated['name']); // "Fluffy Cat!" -> "fluffy-cat"
            $filename = $cleanPetName . '-' . time() . '-' . $file->getClientOriginalExtension(); // "fluffy-cat-16987654
            $path = $file->storeAs('pets/profile_photos', $filename, 'public');
            $validated['pet_profile_path'] = $path; // Save path to DB
        }

        $validated['user_id'] = Auth::user()->id;

        try {
            $pet = Pet::create($validated);
            return redirect()->route('pets.index')->with('success', "Pet {$pet->name} was added successfully.");
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Failed to add pet: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {

        $pet = Pet::findOrFail($id);

        // Check if the user has permission to delete the pet listing
        if (!Auth::user()->can('delete pet listing')) {
            abort(403, 'Unauthorized action');
        }

        // Delete the pet
        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet listing deleted successfully.');
    }

    public function update(Request $request, $id)
    {

        $pet = Pet::findOrFail($id);

        // Check if the user has permission to edit the pet listing
        if (!Auth::user()->can('edit pet listing')) {
            abort(403, 'Unauthorized action');
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:50',
            'breed' => 'required|string|max:100',
            'allergies' => 'nullable|string',
            'description' => 'nullable|string',
            'profile' => 'nullable|image|max:51200',
            'sex' => 'sometimes|in:M,F',
            'species' => 'sometimes|integer|in:0,1',
            'vaccination' => 'required|integer|in:0,1,3',
            'spayed_neutered' => 'sometimes|boolean',
        ]);



        // Handle the profile image upload if provided
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $cleanPetName = Str::slug($validated['name']); // "Fluffy Cat!" -> "fluffy-cat"
            $filename = $cleanPetName . '-' . time() . '-' . $file->getClientOriginalExtension(); // "fluffy-cat-16987654
            $path = $file->storeAs('pets/profile_photos', $filename, 'public');
            $validated['pet_profile_path'] = $path; // Save path to DB
        }

        try {
            // Update the pet with validated data
            $pet->update($validated);
            return redirect()->route('pets.index')->with('success', 'Pet listing updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Failed to update pet: ' . $e->getMessage());
        }

        return redirect()->route('pets.index')->with('success', 'Pet listing updated successfully.');
    }

    public function edit($id)
    {

        $pet = Pet::findOrFail($id);

        // Check if the user has permission to edit the pet listing
        if (!Auth::user()->can('edit pet listing')) {
            abort(403, 'Unauthorized action');
        }

        return view('pages.pets.edit', compact('pet'));
    }
}
