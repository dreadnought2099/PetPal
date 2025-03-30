<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    // Vaccination status constants
    const VACCINATION_NONE = 0;
    const VACCINATION_PARTIAL = 1;
    const VACCINATION_FULL = 3;

     // Species constants
     const SPECIES_DOG = 0;
     const SPECIES_CAT = 1;
 

    protected $fillable = [
        'name',
        'age',
        'breed',
        'allergies',
        'pet_profile_path',
        'sex',
        'species', // Uses Constants
        'vaccination', // Uses Constants
        'spayed_neutered',
        'user_id'
    ];

    protected $casts = [
        'spayed_neutered' => 'boolean',
        'vaccination' => 'integer',
        'species' => 'integer'
    ];

     // Accessors for human-readable values
    public function getVaccinationTextAttribute() {
        return match($this->vaccination) {
            self::VACCINATION_NONE => 'None',
            self::VACCINATION_PARTIAL => 'Partially',
            self::VACCINATION_FULL => 'Fully',
            default => 'Unknown',
        };
    }

     // Accessors for human-readable values
    public function getSpeciesTextAttribute() {
        return match($this->species) {
            self::SPECIES_DOG => 'Dog',
            self::SPECIES_CAT => 'Cat',
            default => 'Unknown',
        };
    }

    // File URL Accessor
    public function getProfilePhotoUrlAttribute() {
        return $this->pet_profile_path
            ? asset('storage/' . $this->pet_profile_path)
            : asset('images/default-pet.png'); // Default Profile Pic for Pets
    }

    // Auto-delete files when pet is deleted
    protected static function boot() {
        
        parent::boot();

        static::deleted(function ($pet) {
            if ($pet->pet_profile_path) {
                Storage::disk('public')->delete($pet->pet_profile_path);
            }
        });
    }


    // Relationships
    public function user() {

        return $this->belongsTo(User::class);
    }

}
