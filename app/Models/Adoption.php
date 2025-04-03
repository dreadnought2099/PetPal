<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Pet;

class Adoption extends Model
{
    use HasFactory;

    protected $table = 'adoption_requests';

    protected $fillable = [
        'user_id', 
        'pet_id', 
        'last_name', 
        'first_name', 
        'middle_name', 
        'address', 
        'contact_number', 
        'dob', 
        'valid_id',
        'previous_experience', 
        'other_pets', 
        'financial_preparedness', 
        'status'
    ];

    // Define relationships properly
    public function user() {
        return $this->belongsTo(User::class, 'user_id'); // Explicitly define foreign key
    }

    public function pet() {
        return $this->belongsTo(Pet::class, 'pet_id'); // Explicitly define foreign key
    }
}
