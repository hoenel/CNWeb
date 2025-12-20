<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Medicine;

class SaleController extends Controller
{
    // Display a listing of sales
    public function index()
    {
        $sales = Sales::with('medicine')->get();
        return view('sales.index', compact('sales'));
    }

    // Show the form for creating a new sale
    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('sales.create', compact('medicines'));
    }

    // Store a newly created sale in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicine,medicine_id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
            'customer_phone' => 'nullable|string|max:10',
        ]);

        // Check if enough stock is available
        $medicine = Medicine::findOrFail($validated['medicine_id']);
        if ($medicine->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.'])->withInput();
        }

        // Create the sale
        Sales::create($validated);

        // Update medicine stock
        $medicine->stock -= $validated['quantity'];
        $medicine->save();

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    // Display the specified sale
    public function show($id)
    {
        $sale = Sales::with('medicine')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // Show the form for editing the specified sale
    public function edit($id)
    {
        $sale = Sales::findOrFail($id);
        $medicines = Medicine::all();
        return view('sales.edit', compact('sale', 'medicines'));
    }

    // Update the specified sale in the database
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicine,medicine_id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
            'customer_phone' => 'nullable|string|max:10',
        ]);

        $sale = Sales::findOrFail($id);
        
        // Restore previous stock
        $oldMedicine = Medicine::findOrFail($sale->medicine_id);
        $oldMedicine->stock += $sale->quantity;
        $oldMedicine->save();

        // Check new medicine stock
        $newMedicine = Medicine::findOrFail($validated['medicine_id']);
        if ($newMedicine->stock < $validated['quantity']) {
            // Revert the stock change
            $oldMedicine->stock -= $sale->quantity;
            $oldMedicine->save();
            return back()->withErrors(['quantity' => 'Insufficient stock available.'])->withInput();
        }

        // Update the sale
        $sale->update($validated);

        // Update new medicine stock
        $newMedicine->stock -= $validated['quantity'];
        $newMedicine->save();

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    // Remove the specified sale from the database
    public function destroy($id)
    {
        $sale = Sales::findOrFail($id);
        
        // Restore stock
        $medicine = Medicine::findOrFail($sale->medicine_id);
        $medicine->stock += $sale->quantity;
        $medicine->save();

        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
