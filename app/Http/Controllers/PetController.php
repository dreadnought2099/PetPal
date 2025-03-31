<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pet;
use Exception;

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
        // Generate filename using the SUBMITTED name (not DB)
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $cleanPetName = Str::slug($validated['name']); // "Fluffy Cat!" -> "fluffy-cat"
            $filename = $cleanPetName . '-' . time() . '-' . $file->getClientOriginalExtension(); // "fluffy-cat-16987654
            $path = $file->storeAs('pets/profile_photos', $filename, 'public');
            $validated['pet_profile_path'] = $path; // Save path to DB
        }

        $validated['user_id'] = auth()->id();
        
        try {
            $pet = Pet::create($validated);
            return redirect()->route('pets.index')->with('success', "Pet {$pet->name} was added successfully.");
        } catch (Exception $e) {
            return redirect()->route('pets.index')->with('error', 'Failed to add pet: '. $e->getMessage());

        }
       
    }
}
