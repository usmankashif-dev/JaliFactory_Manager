<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PartyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'party_name' => 'required|string|max:255',
        ]);

       
        $party = Party::create([
            'name' => $request->party_name,
        ]);

        return response()->json($party);
    }
}
    