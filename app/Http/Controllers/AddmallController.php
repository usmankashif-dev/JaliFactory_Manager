<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mall;
use App\Models\Dropdown;
use App\Models\Party; 
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AddmallController extends Controller
{
    public function showAddMallForm()
    {
        $party = Party::all(); 
        return
       Inertia::render('Add-mall', [
        'party' => $party
    ]);
    }
    
public function index()
{
    $malls = Mall::all();
    return Inertia::render('mall-view', [
        'malls' => $malls
    ]);
}


public function addmallform(Request $request)
{
    $validated = $request->validate([
        'party' => 'required|string',
        'input1' => 'required|numeric|min:0',
        'input2' => 'required|numeric|min:0',
        'input3' => 'required|numeric|min:0',
        'input4' => 'required|string',
        'input5' => 'required|string',  
        'input7' => 'required|string',  
    ]);

    // lot = length-date-gauge-availableqty
    $lot = $request->input2 . '-' . $request->input5 . '-' . $request->input1 . '-' . $request->input7;

    Mall::create([
        'party' => $request->party,
        'input1' => $request->input1,
        'input2' => $request->input2,
        'input3' => $request->input3,
        'input4' => $request->input4,
        'input5' => $request->input5,
        'input7' => $request->input7,
        'availableqty' => $request->input7, 
        'lot'    => $lot,
    ]);

    return redirect()->route('mall-view');
}


    public function deleteMall($id)
    {
        $mall = Mall::findOrFail($id);
        $mall->delete();
        return redirect()->back()->with('message', 'Mall deleted successfully.');
    }

    public function editMall($id)
    {
        $mall = Mall::findOrFail($id);
        return Inertia::render('Edit-mall', [
            'mall' => $mall
        ]);
    }

    public function updateMall(Request $request, $id)
    {
        $validated = $request->validate([
            'party' => 'required|string',
            'input1' => 'required|numeric|min:0',
            'input2' => 'required|numeric|min:0',
            'input3' => 'required|numeric|min:0',
            'input4' => 'required|string',
            'input5' => 'required|string',
            'availableqty' => 'required|numeric|min:0',
        ]);
        
        $mall = Mall::findOrFail($id);
        $mall->update($validated);

        return redirect()->route('mall-view');
    }

    public function showAddMallForm1()
    {
        $party = \DB::table('parties')->get(); 
        return Inertia::render('Add-mall', [
            'party' => $party
        ]);
    }
    

    public function storeparty(Request $request)
    {
        $request->validate(['party_name' => 'required|unique:dropdowns,name']);
        $party = Dropdown::create(['name' => $request->party_name]);
        return response()->json(['id' => $party->id, 'name' => $party->name]);
    }
   
public function newordersv($id)
{
    $mall = Mall::findOrFail($id);
    // Use the same view but pass a flag to indicate 'make order' mode (no quantity field)
    return Inertia::render('new-order', [
        'mall' => $mall,
        'make_order_full_quantity' => true // Pass this flag to the Vue component
    ]);
}
     public function makenewordersv($id)
    {
        $mall = Mall::findOrFail($id);
        return Inertia::render('new-order', [
        'mall' => $mall,
        'make_order_full_quantity' => true // Pass this flag to the Vue component
    ]);
    }

public function store(Request $request)
{
    // Add validation for all required fields
    $validated = $request->validate([
        'orderedqty' => 'required|numeric|min:1',
        'olenght' => 'required|numeric|min:1',
        'ogauge' => 'required',
        'peice' => 'required',
        'bundlewidht' => 'required',
        'sheetperbundle' => 'required|numeric|min:1',
        'jalilenght' => 'required',
        'mall_id' => 'required|exists:malls,id',
        'dateno' => 'required|date',
    ]);

    $mall_id = $request->mall_id;
    // Always fetch the latest mall data
    $mall = Mall::find($mall_id);

    if (!$mall) {
        return redirect()->back()->with('error', 'Mall not found!');
    }

    $widht = $mall->input3 ?? 0;
    $lenght = $mall->input2 ?? 0;

    // Check if enough stock is available
    if ($mall->availableqty < $request->orderedqty) {
        return redirect()->back()->with('Not Enough Stock Available');
    }

    // Place the order
    $orderedpeices = 0;

    $cutsheetqty = 0;
    if ($lenght != 0) {
        $cutsheetqty = ($lenght / $request->olenght) * $request->orderedqty;
    }
    $rem = $cutsheetqty;
    $order = Order::create([
        'orderedqty' => $request->orderedqty,
        'olenght' => $request->olenght,
        'ogauge' => $request->ogauge,
        'peice' => $request->peice,
        'lenght' => $lenght,
        'widht' => $widht,
        'gauge' => $mall->input1,
        'mall_id' => $request->mall_id,
        'dateno' => $request->dateno,
        'lot' => $mall->lot,
        'rem' => $cutsheetqty, 
        'orgsheet' => $mall->input2 . '-' . $mall->input3 . '-' . $mall->input1,
        'cutsheet' => $request->olenght . '-' . $mall->input3 . '-' . $mall->input1,
        'bundlewidht' => $request->bundlewidht,
        'sheetperbundle' => $request->sheetperbundle,
        'partyorder' => $mall->party,
        'cutsheetqty' => $cutsheetqty,
        'jalilenght' => $request->jalilenght,
        'orderedpeices' => $orderedpeices,
    ]);
    Log::info('Order created', ['order_id' => $order->id]);

    // If ordered length is less than mall's length, create a new mall with the remaining length and ordered quantity
    if ($request->olenght < $mall->input2) {
        $remainingLength = $mall->input2 - $request->olenght;

        // Duplicate the mall with the remaining length and ordered quantity
        $newMall = $mall->replicate();
        $newMall->input2 = $remainingLength;
        $newMall->availableqty = $request->orderedqty;
        $newMall->save();

        // The original mall keeps its length, but availableqty is reduced by orderedqty
        $mall->availableqty -= $request->orderedqty;

        // Delete the new mall if its quantity is zero
        if ($newMall->availableqty == 0) {
            $newMall->delete();
        }
    } else {
        // If no split, just reduce availableqty as usual
        $mall->availableqty -= $request->orderedqty;
    }

    // Delete the original mall if its quantity is zero
    if ($mall->availableqty == 0) {
        $mall->delete();
    } else {
        $mall->save();
    }
    return redirect()->route('order-view')->with('success', 'Order placed successfully!');
}
public function makestore(Request $request)
{
    // Validation: do not require quantity or length, but require sheetperbundle
    $validated = $request->validate([
        'ogauge' => 'required',
        'peice' => 'required',
        'bundlewidht' => 'required',
        'sheetperbundle' => 'required|numeric|min:1',
        'jalilenght' => 'required',
        'mall_id' => 'required|exists:malls,id',
        'dateno' => 'required|date',
    ]);

    $mall_id = $request->mall_id;
    $mall = Mall::find($mall_id);

    if (!$mall) {
        return redirect()->back()->with('error', 'Mall not found!');
    }

    $widht = $mall->input3 ?? 0;
    $lenght = $mall->input2 ?? 0;

    // Use all available quantity and full length for the order
    $orderedqty = $mall->availableqty;
    $olenght = $lenght;

    $orderedpeices = $request->orderedpeices;
    $cutsheetqty = 0;
    if ($orderedpeices && $orderedpeices > 0) {
        $cutsheetqty = $orderedqty * $orderedpeices;
        $olenght = $mall->input2 / $orderedpeices;
    }

    $order = Order::create([
        'orderedqty' => $orderedqty,
        'olenght' => $olenght,
        'ogauge' => $request->ogauge,
        'peice' => $request->peice,
        'lenght' => $lenght,
        'widht' => $widht,
        'gauge' => $mall->input1,
        'mall_id' => $mall_id,
        'dateno' => $request->dateno,
        'lot' => $mall->lot,
        'rem' => $cutsheetqty,
        'orgsheet' => $mall->input2 . '-' . $mall->input3 . '-' . $mall->input1,
        'cutsheet' => $olenght . '-' . $mall->input3 . '-' . $mall->input1,
        'bundlewidht' => $request->bundlewidht,
        'sheetperbundle' => $request->sheetperbundle,
        'partyorder' => $mall->party,
        'cutsheetqty' => $cutsheetqty,
        'jalilenght' => $request->jalilenght,
        'orderedpeices' => $orderedpeices,
    ]);
    Log::info('Order created', ['order_id' => $order->id]);
    $mall->delete();

    return redirect()->route('order-view')->with('success', 'Order placed successfully!');
}
 public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('message', 'Order deleted successfully.');
    }

public function newOrder()
{
    return Inertia::render('new-order');
}

public function showOrders(Request $request)
{
    $query = Order::with('mall')->latest();

    // Apply filters if present
    if ($request->filled('from_date')) {
        $query->whereDate('dateno', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('dateno', '<=', $request->to_date);
    }
    if ($request->filled('partyorder')) {
        $query->where('partyorder', 'like', '%' . $request->partyorder . '%');
    }
    if ($request->filled('lot')) {
        $query->where('lot', 'like', '%' . $request->lot . '%');
    }
    if ($request->filled('orgsheet')) {
        $query->where('orgsheet', 'like', '%' . $request->orgsheet . '%');
    }
    if ($request->filled('cutsheet')) {
        $query->where('cutsheet', 'like', '%' . $request->cutsheet . '%');
    }
    if ($request->filled('peice')) {
        $query->where('peice', 'like', '%' . $request->peice . '%');
    }
    if ($request->filled('ogauge')) {
        $query->where('ogauge', 'like', '%' . $request->ogauge . '%');
    }
    if ($request->filled('widht')) {
        $query->where('widht', 'like', '%' . $request->widht . '%');
    }
    if ($request->filled('jalilenght')) {
        $query->where('jalilenght', 'like', '%' . $request->jalilenght . '%');
    }
    if ($request->filled('sheetperbundle')) {
        $query->where('sheetperbundle', 'like', '%' . $request->sheetperbundle . '%');
    }
    if ($request->filled('orderedqty')) {
        $query->where('orderedqty', $request->orderedqty);
    }
    if ($request->filled('cutsheetqty')) {
        $query->where('cutsheetqty', $request->cutsheetqty);
    }
    if ($request->filled('rem')) {
        $query->where('rem', $request->rem);
    }

    $orders = $query->get();
    // Update each order's rem to the latest availableqty from the related mall
    foreach ($orders as $order) {
        if ($order->mall) {
            $order->rem = $order->mall->availableqty;
        }
    }
    return Inertia::render('order-view', [
        'orders' => $orders
    ]);
}
public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        return Inertia::render('Edit-order', [
            'order' => $order
        ]);
    }
        public function updateorder(Request $request, $id)
    {
           $validated = $request->validate([
        'orderedqty' => 'required|numeric|min:1',
        'olenght' => 'required|numeric|min:1',
        'ogauge' => 'required',
        'peice' => 'required',
        'bundlewidht' => 'required',
        'sheetperbundle' => 'required|numeric|min:1',
        'jalilenght' => 'required',
        'mall_id' => 'required|exists:malls,id',
        'dateno' => 'required|date',
    ]);
        
        $order = Order::findOrFail($id);
        $order->update($validated);
$order->save();

        return redirect()->route('order-view');
    }

    // Show Make Order page (full quantity, no quantity field)
    public function showMakeOrder($id)
    {
        $mall = Mall::findOrFail($id);
        return Inertia::render('make-order', [
            'mall' => $mall,
            'make_order_full_quantity' => true
        ]);
    }
}
