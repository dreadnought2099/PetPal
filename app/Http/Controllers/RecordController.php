<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Exception;

class RecordController extends Controller
{
    public function index()
    {
        $records = Record::all();
        return view('pages.records.index', compact('records'));
    }

    // Responsible for handling creation of  records
    public function create()
    {
        return view('pages.records.create');
    }

    // Responsible for handling storing of  records
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'author' => 'required|string|max:100',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'category' => 'required|string|max:50',
            'isbn' => 'required|unique:records,isbn',
        ]);


        try {
            $record = Record::create($request->all());
            return redirect()->route('records.index')->with('success', "Record with ID {$record->id} was added successfully!");

        } catch (Exception $e) {
            return redirect()->route('records.index')->with('error', 'Failed to add record. Please try again.');

        }
    }

    // Responsible for handling editing of  records
    public function edit(Record $record)
    {
        return view('pages.records.edit', compact('record'));
    }

    // Responsible for handling updates of  records
    public function update(Request $request, Record $record)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:200',
            'author' => 'required|string|max:100',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'category' => 'required|string|max:50',
            'isbn' => 'required|unique:records,isbn,' . $record->id,
        ]);

        $record->fill($validatedData);

        if ($record->isDirty()) {
            $record->save();
            return redirect()->route('records.index')->with('success', "Record with ID {$record->id} was updated successfully.");
        }

        // dd($request->all());
        return redirect()->route('records.index')->with('info', "No changes were made in ID {$record->id}.");
    }

    // Responsible for  destroying of  records
    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', "Record with ID {$record->id} was deleted successfully!");
    }
}
