<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);
        Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0) {
            return back()->withErrors(['error' => 'Supplier tidak dapat dihapus karena memiliki riwayat pembelian!']);
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}