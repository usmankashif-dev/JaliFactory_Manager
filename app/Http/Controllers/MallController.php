<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mall;
use App\Models\Party;
use Inertia\Inertia;

class MallController extends Controller
{
    public function index(Request $request)
    {
        $query = Mall::query();
    
        if ($request->filled('party')) {
            $query->where('party', 'like', '%' . $request->party . '%');
        }
    
        if ($request->filled('input1')) {
            $query->where('input1', $request->input1);
        }
    
        if ($request->filled('input2')) {
            $query->where('input2', $request->input2);
        }
    
        if ($request->filled('input3')) {
            $query->where('input3', $request->input3);
        }
    
        if ($request->filled('input4')) {
            $query->where('input4', 'like', '%' . $request->input4 . '%');
        }
    
        if ($request->filled('input5')) {
            $query->where('input5', 'like', '%' . $request->input5 . '%');
        }
    
        if ($request->filled('input7')) {
            $query->where('input7', 'like', '%' . $request->input7 . '%');
        }
        if ($request->filled('lot')) {
            $query->where('lot', 'like', '%' . $request->lot . '%');
        }
    
        $malls = $query->get();
        $parties = Party::all();
        return Inertia::render('MallView', [
            'malls' => $malls,
            'parties' => $parties
        ]);
    }
    public function indexd(Request $request)
    {
        $query = Mall::query();
    
        if ($request->filled('party')) {
            $query->where('party', 'like', '%' . $request->party . '%');
        }
    
        if ($request->filled('input1')) {
            $query->where('input1', $request->input1);
        }
    
        if ($request->filled('input2')) {
            $query->where('input2', $request->input2);
        }
    
        if ($request->filled('input3')) {
            $query->where('input3', $request->input3);
        }
    
        if ($request->filled('input4')) {
            $query->where('input4', 'like', '%' . $request->input4 . '%');
        }
    
        if ($request->filled('input5')) {
            $query->where('input5', 'like', '%' . $request->input5 . '%');
        }

        if ($request->filled('input7')) {
            $query->where('input7', 'like', '%' . $request->input7 . '%');
        }
        if ($request->filled('lot')) {
            $query->where('lot', 'like', '%' . $request->lot . '%');
        }

        $malls = $query->get();
    
        return Inertia::render('Dashboard', [
            'malls' => $malls
        ]);
    }

    public function update(Request $request, $id)
    {
        $mall = Mall::findOrFail($id);

        $mall->party = $request->input('party');
        $mall->input1 = $request->input('input1');
        $mall->input2 = $request->input('input2');
        $mall->input3 = $request->input('input3');
        $mall->input4 = $request->input('input4');
        $mall->input5 = $request->input('input5');
        $mall->availableqty = $request->input('availableqty');
        $mall->save();

        return redirect()->back()->with('success', 'Mall updated!');
    }
    
}
