<?php

// app/Http/Controllers/OtherFeeController.php

namespace App\Http\Controllers;

use App\Models\OtherFee;
use Illuminate\Http\Request;

class OtherFeeController extends Controller
{
    public function update(Request $request, OtherFee $fee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $fee->update([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with('success', 'Fee updated successfully.');
    }

    public function index()
    {
        $fees = OtherFee::all();
        $trashedFees = OtherFee::onlyTrashed()->get();
        return view('fees.index', compact('fees', 'trashedFees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        OtherFee::create($request->only('name', 'amount'));

        return redirect()->back()->with('success', 'Other Fee added successfully.');
    }

    public function destroy(OtherFee $fee)
    {
        $fee->delete();
        return back()->with('success', 'Fee moved to trash.');
    }

    public function restore($id)
    {
        $fee = OtherFee::withTrashed()->findOrFail($id);
        $fee->restore();
        return back()->with('success', 'Fee restored successfully.');
    }

    public function forceDelete($id)
    {
        $fee = OtherFee::withTrashed()->findOrFail($id);
        $fee->forceDelete();
        return back()->with('success', 'Fee permanently deleted.');
    }

    public function toggleStatus(OtherFee $fee)
    {
        $fee->status = $fee->status === 'active' ? 'inactive' : 'active';
        $fee->save();

        return back()->with('success', 'Fee status updated.');
    }
}
