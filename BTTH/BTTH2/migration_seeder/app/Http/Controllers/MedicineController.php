<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    // Display a listing of medicines
    public function index()
    {
        $medicines = Medicine::all();
        return view('medicines.index', compact('medicines'));
    }

    // Show the form for creating a new medicine
    public function create()
    {
        return view('medicines.create');
    }

    // Store a newly created medicine in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'dosage' => 'required|string|max:50',
            'form' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Medicine::create($validated);
        return redirect()->route('medicines.index')->with('success', 'Medicine created successfully.');
    }

    // Display the specified medicine
    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('medicines.show', compact('medicine'));
    }

    // Show the form for editing the specified medicine
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('medicines.edit', compact('medicine'));
    }

    // Update the specified medicine in the database
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'dosage' => 'required|string|max:50',
            'form' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->update($validated);
        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    // Remove the specified medicine from the database
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
