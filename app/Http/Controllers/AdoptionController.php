<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdoptionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $adoptions = Adoption::with('user', 'pet')->get();
        $pets = Pet::all(); // Fetch all pets
        return view('pages.adoptions.index', compact('adoptions', 'pets'));
    }


    public function store(Request $request)
    {
        Log::info('Adoption Request Data:', $request->all());

        try {
            $validated = $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'address' => 'required|string',
                'contact_number' => 'required|string|max:20',
                'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
                'valid_id' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'valid_id_back' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
                'previous_experience' => 'required|in:yes,no',
                'other_pets' => 'required|in:yes,no',
                'financial_preparedness' => 'required|in:yes,no',
            ], [
                'dob.before_or_equal' => 'You must be atleast 18 years old to apply for an adoption.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Please enter a valid date.',
            ]);

            // Check pet availability
            $pet = Pet::findOrFail($validated['pet_id']);

            if (!$pet->isAvailableForAdoption()) {
                return back()->with('error', 'This pet is not available for adoption.');
            }

            // Check for existing pending request
            $existingRequest = Adoption::where('user_id', Auth::id())
                ->where('pet_id', $validated['pet_id'])
                ->latest()
                ->first();

            if ($existingRequest) {
                if (in_array($existingRequest->status, ['pending', 'approved'])) {
                    return back()->with("error', 'You already have an active adoption request for $pet->name.");
                }

                $existingRequest->update(['status' => 'archived']);
            }

            // File upload
            if ($request->hasFile('valid_id')) {
                $file = $request->file('valid_id');
                $filename = time() . '-' . Str::slug($file->getClientOriginalName());
                $path = $file->storeAs('adoption/valid_ids', $filename, 'public');
                $validated['valid_id'] = $path;
                Log::info('File stored at path', ['path' => $path]);
            }

            if ($request->hasFile('valid_id_back')) {
                $fileBack = $request->file('valid_id_back');
                $filenameBack = time() . '-' . Str::slug($fileBack->getClientOriginalName());
                $pathBack = $fileBack->storeAs('adoption/valid_ids', $filenameBack, 'public');
                $validated['valid_id_back'] = $pathBack;
                Log::info('Valid ID Back file stored at path', ['path' => $pathBack]);
            }

            // Create adoption request
            $adoptionRequest = Adoption::create([
                'user_id' => Auth::id(),
                'pet_id' => $validated['pet_id'],
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'dob' => $validated['dob'],
                'valid_id' => $validated['valid_id'],
                'valid_id_back' => $validated['valid_id_back'],
                'previous_experience' => $validated['previous_experience'],
                'other_pets' => $validated['other_pets'],
                'financial_preparedness' => $validated['financial_preparedness'],
                'status' => 'pending',
            ]);

            // Update pet status to pending (not adopted yet!)
            $pet->update(['status' => Pet::STATUS_PENDING]);

            return redirect()->route('adopt.log')->with('success', "Adoption request submitted successfully!");
        } catch (\Exception $e) {
            Log::error("Adoption Request Error: " . $e->getMessage());
            return back()->with('error', 'Failed to submit adoption request: ' . $e->getMessage());
        }
    }

    public function approve(Adoption $adoption)
    {
        $this->authorize('approve', $adoption);

        DB::transaction(function () use ($adoption) {
            // Update adoption status
            $adoption->update(['status' => 'approved']);

            // Update pet status
            $adoption->pet->update(['status' => Pet::STATUS_ADOPTED]);

            // Reject all other pending requests for this pet
            Adoption::where('pet_id', $adoption->pet_id)
                ->where('id', '!=', $adoption->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return back()->with('success', 'Adoption approved successfully!');
    }

    public function reject(Adoption $adoption)
    {
        $this->authorize('reject', $adoption);

        DB::transaction(function () use ($adoption) {
            $adoption->update(['status' => 'rejected']);

            // If this was the only pending request, make pet available again
            $hasOtherPending = Adoption::where('pet_id', $adoption->pet_id)
                ->where('status', 'pending')
                ->exists();

            if (!$hasOtherPending) {
                $adoption->pet->update(['status' => Pet::STATUS_AVAILABLE]);
            }
        });

        return back()->with('success', 'Adoption request rejected.');
    }

    public function destroy(Adoption $adoption)
    {
        // Log the current user and adoption request details for debugging
        Log::info('Attempting to delete adoption request', [
            'user_id' => Auth::id(),
            'adoption_user_id' => $adoption->user_id,
            'adoption_status' => $adoption->status,
        ]);

        // Ensure only the owner can delete their own adoption request if it's still pending
        if (Auth::id() !== $adoption->user_id) {
            abort(403, 'Unauthorized: You are not the owner of this adoption request.');
        }

        if ($adoption->status !== 'pending') {
            return back()->with('error', 'Only pending adoption requests can be deleted.');
        }

        // Perform deletion (handle soft deletes if applicable)
        if (method_exists($adoption, 'forceDelete')) {
            $adoption->forceDelete(); // Use forceDelete if using SoftDeletes
        } else {
            $adoption->delete();
        }

        // Log success message
        Log::info("Adoption request with ID {$adoption->id} deleted successfully", [
            'deleted_by' => Auth::id(),
            'adoption_id' => $adoption->id,
        ]);

        return back()->with('success', "Adoption request with ID {$adoption->id} deleted successfully.");
    }


    public function adoptionLog()
    {
        $user = Auth::user();

        if ($user->hasRole('Adopter')) {
            // Only show the adoption records for the current Adopter
            $adoptions = Adoption::where('user_id', $user->id)->get();
        } else {
            // For Shelter and Admin roles, show all adoptions
            $adoptions = Adoption::all();
        }

        return view('pages.adoptions.adoption-log', compact('adoptions'));
    }

    public function edit(Adoption $adoption)
    {

        $adoptions = Adoption::with('user', 'pet')->get();
        $pets = Pet::all();

        if (Auth::id() !== $adoption->user_id || $adoption->status !== 'pending') {
            abort(403, 'Unauthorized action');
        }

        return view('pages.adoptions.edit', compact('adoption', 'pets'));
    }

    public function update(Request $request, Adoption $adoption)
    {
        if (Auth::id() !== $adoption->user_id || $adoption->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
            'valid_id' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:51200',
            'valid_id_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:51200',
            'previous_experience' => 'required|in:yes,no',
            'other_pets' => 'required|in:yes,no',
            'financial_preparedness' => 'required|in:yes,no',
        ], [
            'dob.before_or_equal' => 'You must be atleast 18 years old to apply for an adoption.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Please enter a valid date.',
        ]);

        if ($request->hasFile('valid_id')) {
            // Delete old file if exists
            if ($adoption->valid_id) {
                Storage::disk('public')->delete($adoption->valid_id);
            }

            // Store new file
            $validated['valid_id'] = $request->file('valid_id')->store('adoption/valid_ids', 'public');
        }

        if ($request->hasFile('valid_id_back')) {
            // Delete old 'valid_id_back' file if it exists
            if ($adoption->valid_id_back) {
                Storage::disk('public')->delete($adoption->valid_id_back);
            }
        
            // Store new 'valid_id_back' file
            $validated['valid_id_back'] = $request->file('valid_id_back')->store('adoption/valid_ids', 'public');
        }

        // Update the adoption record with validated data (except for the valid_id, which is handled separately)
        $adoption->update($validated);

        DB::transaction(function () use ($adoption, $validated) {
            //  Check if pet_id is being changed
            if ($adoption->pet_id != $validated['pet_id']) {
                //  Revert old pet's status to available
                $adoption->pet->update(['status' => Pet::STATUS_AVAILABLE]);

                //  Update new pet's status to pending
                $newPet = Pet::findOrFail($validated['pet_id']);
                $newPet->update(['status' => Pet::STATUS_PENDING]);
            }

            //  Apply validated data
            $adoption->fill($validated);

            if ($adoption->isDirty()) {
                $adoption->save();
            }
        });

        return redirect()->route('adopt.log')->with('success', "Adoption request with ID {$adoption->id} updated successfully.");
    }


    public function create($pet)
    {
        $selectedPet = Pet::find($pet);
        $pets = Pet::all();

        return view('pages.adoptions.index', compact('pets', 'selectedPet'));
    }

    public function pending()
    {
        $adoptions = Adoption::with('pet', 'user')  // Load pet and user details for each adoption request
            ->where('status', 'pending')
            ->get();

        return view('pages.adoptions.pending-request', compact('adoptions'));
    }
}
