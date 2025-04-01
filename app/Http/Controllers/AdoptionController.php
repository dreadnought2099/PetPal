<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'dob' => 'required|date',
                'previous_experience' => 'required|in:yes,no',
                'other_pets' => 'required|in:yes,no',
                'financial_preparedness' => 'required|in:yes,no',
            ]);

            $adoptionRequest = Adoption::create([
                'user_id' => Auth::id(), // Attach the adopter's ID
                'pet_id' => $request->pet_id,
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'dob' => $validated['dob'],
                'previous_experience' => $validated['previous_experience'],
                'other_pets' => $validated['other_pets'],
                'financial_preparedness' => $validated['financial_preparedness'],
                'status' => 'pending',
            ]);

            return redirect()->route('adopt.log')->with('success', "Adoption request (ID: {$adoptionRequest->id}) submitted successfully!");
        } catch (\Exception $e) {
            Log::error("Adoption Request Error: " . $e->getMessage());
            return back()->with('error', 'Failed to submit adoption request. Please try again.');
        }
    }


    public function approve(Adoption $adoption)
    {
        $this->authorize('approve', $adoption);
        $adoption->update(['status' => 'approved']);

        return back()->with('success', 'Adoption request approved.');
    }


    public function reject(Adoption $adoption)
    {
        $this->authorize('reject', $adoption);
        $adoption->update(['status' => 'rejected']);

        return back()->with('error', 'Adoption request rejected.');
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
            $adoptions = Adoption::where('user_id', $user->id)->get();
        } else {
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
            'dob' => 'required|date',
            'previous_experience' => 'required|in:yes,no',
            'other_pets' => 'required|in:yes,no',
            'financial_preparedness' => 'required|in:yes,no',
        ]);

        $adoption->fill($validated);

        if ($adoption->isDirty()) {
            $adoption->save();
            return redirect()->route('adopt.log')->with('success', "Adoption request with ID {$adoption->id} updated successfully.");
        }

        return redirect()->route('adopt.log')->with('info', "No changes were made in ID {$adoption->id}.");

    }


    public function create(Request $request)
    {
        $selectedPet = $request->input('pet_id') ? Pet::find($request->input('pet_id')) : null;
        $pets = Pet::all(); // Fetch all available pets

        return view('pages.adoptions.index', compact('pets', 'selectedPet'));
    }

    public function pending()
    {
        $adoptions = Adoption::with('pet')  // Load pet details for each adoption request
            ->where('status', 'pending')
            ->get();

        return view('pages.adoptions.pending-request', compact('adoptions'));
    }
}
