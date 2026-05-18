<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MachineController extends Controller
{
    public function create($orderId)
    {
        $order = Order::findOrFail($orderId);
        return Inertia::render('MachineForm', [
            'order' => $order
        ]);
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $validated = $request->validate([
            'machinedate' => 'required|date',
            'machinenumber' => 'required|integer',
            'machineqty' => 'required|integer',
        ]);
        $machine = new Machine();
        $machine->machinedate = $validated['machinedate'];
        $machine->machinenumber = $validated['machinenumber'];
        $machine->machineqty = $validated['machineqty'];
        $machine->olenght = $order->olenght;
        $machine->peice = $order->peice;
        $machine->ogauge = $order->ogauge;
        $machine->cutsheet = $order->cutsheet;
        $machine->lot = $order->lot;
        $machine->bundlewidht = $order->bundlewidht;
        $machine->sheetperbundle = $order->sheetperbundle;
        $machine->partyorder = $order->partyorder;
        $machine->jalilenght = $order->jalilenght;
        $machine->orderedpeices = $order->orderedpeices;
        $machine->save();

        // Decrease rem in order by machineqty
        $order->rem = max(0, $order->rem - $machine->machineqty);
        $order->save();

        // Delete order if rem hits 0
        if ($order->rem == 0) {
            $order->delete();
        }

        return redirect()->route('machine-view')->with('success', 'Sent to machine successfully!');
    }

    public function index(Request $request)
    {
        $query = Machine::query();
        if ($request->filled('machinedate')) {
            $query->whereDate('machinedate', $request->machinedate);
        }
        if ($request->filled('machinenumber')) {
            $query->where('machinenumber', 'like', '%' . $request->machinenumber . '%');
        }
        if ($request->filled('machineqty')) {
            $query->where('machineqty', $request->machineqty);
        }
        if ($request->filled('olenght')) {
            $query->where('olenght', 'like', '%' . $request->olenght . '%');
        }
        if ($request->filled('peice')) {
            $query->where('peice', 'like', '%' . $request->peice . '%');
        }
        if ($request->filled('ogauge')) {
            $query->where('ogauge', 'like', '%' . $request->ogauge . '%');
        }
        if ($request->filled('cutsheet')) {
            $query->where('cutsheet', 'like', '%' . $request->cutsheet . '%');
        }
        if ($request->filled('lot')) {
            $query->where('lot', 'like', '%' . $request->lot . '%');
        }
        if ($request->filled('bundlewidht')) {
            $query->where('bundlewidht', 'like', '%' . $request->bundlewidht . '%');
        }
        if ($request->filled('sheetperbundle')) {
            $query->where('sheetperbundle', 'like', '%' . $request->sheetperbundle . '%');
        }
        if ($request->filled('partyorder')) {
            $query->where('partyorder', 'like', '%' . $request->partyorder . '%');
        }
        if ($request->filled('jalilenght')) {
            $query->where('jalilenght', 'like', '%' . $request->jalilenght . '%');
        }
        if ($request->filled('orderedpeices')) {
            $query->where('orderedpeices', 'like', '%' . $request->orderedpeices . '%');
        }
        $machines = $query->latest()->get();
        return Inertia::render('MachineView', [
            'machines' => $machines
        ]);
    }
    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
         $machine->delete();
        return redirect()->back()->with('message', 'Order deleted successfully.');
    }
    public function edit($id)
    {
        $machine = Machine::findOrFail($id);
        $order = Order::where('partyorder', $machine->partyorder)->first();
        return Inertia::render('MachineEdit', [
            'machine' => $machine,
            'order' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);
        $order = Order::where('partyorder', $machine->partyorder)->first();
        $validated = $request->validate([
            'machinedate' => 'required|date',
            'machinenumber' => 'required|integer',
            'machineqty' => 'required|integer',
        ]);
        $oldQty = $machine->machineqty;
        $newQty = $validated['machineqty'];
        $diff = $newQty - $oldQty;
        $machine->machinedate = $validated['machinedate'];
        $machine->machinenumber = $validated['machinenumber'];
        $machine->machineqty = $newQty;
        $machine->save();
        if ($order) {
            $order->rem = max(0, $order->rem - $diff);
            $order->save();
            if ($order->rem == 0) {
                $order->delete();
            }
        }
        return redirect()->route('machine-view')->with('success', 'Machine updated successfully!');
    }

    public function finishForm($id)
    {
        $machine = Machine::findOrFail($id);
        return Inertia::render('MachineFinish', [
            'machine' => $machine
        ]);
    }

    public function finish(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);
        $validated = $request->validate([
            'finish_date' => 'required|date',
            'finish_qty' => 'required|integer|min:1',
            'packed_by' => 'required|string|max:255',
        ]);
        $finishQty = $validated['finish_qty'];
        if ($finishQty > $machine->machineqty) {
            return back()->withErrors(['finish_qty' => 'Finish quantity cannot exceed machine quantity.']);
        }
        // Save finished stock info
        DB::table('finished_stocks')->insert([
            'machine_id' => $machine->id,
            'date' => $validated['finish_date'],
            'party_name' => $machine->partyorder,
            'khana' => $machine->peice,
            'b_width' => $machine->bundlewidht,
            'b_length' => $machine->olenght,
            'sheets_per_bundle' => $machine->sheetperbundle,
            'sheet_size' => $machine->cutsheet,
            'lot' => $machine->lot,
            'bundle' => $finishQty,
            'packed_by' => $validated['packed_by'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Subtract from machine
        $machine->machineqty -= $finishQty;
        if ($machine->machineqty <= 0) {
            $machine->delete();
        } else {
            $machine->save();
        }
        return redirect()->route('stock')->with('success', 'Stock finished and saved!');
        
    }

    public function stock(Request $request)
    {
        $query = \DB::table('finished_stocks');
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        if ($request->filled('party_name')) {
            $query->where('party_name', 'like', '%' . $request->party_name . '%');
        }
        if ($request->filled('piece')) {
            $query->where('piece', 'like', '%' . $request->piece . '%');
        }
        if ($request->filled('b_width')) {
            $query->where('b_width', 'like', '%' . $request->b_width . '%');
        }
        if ($request->filled('b_length')) {
            $query->where('b_length', 'like', '%' . $request->b_length . '%');
        }
        if ($request->filled('lot')) {
            $query->where('lot', 'like', '%' . $request->lot . '%');
        }
        $stocks = $query->orderByDesc('date')->get();
        return Inertia::render('Stock', [
            'stocks' => $stocks
        ]);
    }
    public function stockDelete($id)
    {
        \DB::table('finished_stocks')->where('id', $id)->delete();
        return redirect()->route('stock')->with('success', 'Stock entry deleted successfully!');
    }

    public function stockEdit($id)
    {
        $stock = \DB::table('finished_stocks')->where('id', $id)->first();
        return Inertia::render('StockEdit', [
            'stock' => $stock
        ]);
    }

    public function stockUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'party_name' => 'required|string',
            'piece' => 'nullable|string',
            'b_width' => 'required',
            'b_length' => 'required',
            'sheets_per_bundle' => 'required',
            'sheet_size' => 'required',
            'lot' => 'required',
            'bundle' => 'required',
        ]);
        \DB::table('finished_stocks')->where('id', $id)->update($validated);
        return redirect()->route('stock')->with('success', 'Stock entry updated successfully!');
    }
    public function bundleForm($id)
    {
        $stock = DB::table('finished_stocks')->where('id', $id)->first();
        return Inertia::render('StockBundleForm', [
            'stock' => $stock
        ]);
    }

    public function bundleStore(Request $request, $id)
    {
        $validated = $request->validate([
            'bundle_date' => 'required|date',
            'sheets_per_bundle' => 'required|integer|min:1',
            'bundle_count' => 'required|integer|min:1',
        ]);
        $stock = DB::table('finished_stocks')->where('id', $id)->first();
        if (!$stock) {
            return redirect()->route('stock')->with('error', 'Stock not found!');
        }
        $totalSheets = $validated['sheets_per_bundle'] * $validated['bundle_count'];
        if ($totalSheets > $stock->bundle) {
            return back()->withErrors(['bundle_count' => 'Not enough finished quantity.']);
        }
        // Subtract from finished stock
        DB::table('finished_stocks')->where('id', $id)->update([
            'bundle' => $stock->bundle - $totalSheets
        ]);
        // Insert into bundle_history so it appears in the chart
        DB::table('bundle_history')->insert([
            'stock_id' => $id,
            'date' => $validated['bundle_date'],
            'sheets_per_bundle' => $validated['sheets_per_bundle'],
            'bundle_count' => $validated['bundle_count'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Store bundle info (could be a new table, for now just pass to view)
        $bundleInfo = [
            'date' => $validated['bundle_date'],
            'sheets_per_bundle' => $validated['sheets_per_bundle'],
            'bundle_count' => $validated['bundle_count'],
            'stock' => $stock
        ];
        return Inertia::render('StockBundleInfo', $bundleInfo);
        $stock = DB::table('finished_stocks')->where('id', $id)->first();
        if ($stock->bundle <= 0) {
            $stock->delete();
        }
         else {
            $stock->save();
         }
    }
    public function bundleChart(Request $request)
    {
        $query = \DB::table('bundle_history')
            ->leftJoin('finished_stocks', 'bundle_history.stock_id', '=', 'finished_stocks.id')
            ->leftJoin('orders', 'finished_stocks.lot', '=', 'orders.lot')
            ->select(
                'bundle_history.*',
                'finished_stocks.party_name as stock_party_name',
                'finished_stocks.lot as stock_lot',
                'orders.cutsheet as stock_cutsheet',
                'finished_stocks.khana as stock_peice',
                'finished_stocks.b_width as stock_widht',
                'finished_stocks.b_length as stock_jalilenght',
                'finished_stocks.packed_by as packed_by',
            );
        if ($request->filled('stock_party_name')) {
            $query->where('finished_stocks.party_name', 'like', '%' . $request->stock_party_name . '%');
        }
        if ($request->filled('stock_lot')) {
            $query->where('finished_stocks.lot', 'like', '%' . $request->stock_lot . '%');
        }
        if ($request->filled('stock_cutsheet')) {
            $query->where('orders.cutsheet', 'like', '%' . $request->stock_cutsheet . '%');
        }
        if ($request->filled('stock_peice')) {
            $query->where('finished_stocks.khana', 'like', '%' . $request->stock_peice . '%');
        }
        if ($request->filled('stock_widht')) {
            $query->where('finished_stocks.b_width', 'like', '%' . $request->stock_widht . '%');
        }
        if ($request->filled('stock_jalilenght')) {
            $query->where('finished_stocks.b_length', 'like', '%' . $request->stock_jalilenght . '%');
        }
        if ($request->filled('date')) {
            $query->where('bundle_history.date', $request->date);
        }
        if ($request->filled('sheets_per_bundle')) {
            $query->where('bundle_history.sheets_per_bundle', $request->sheets_per_bundle);
        }
        if ($request->filled('bundle_count')) {
            $query->where('bundle_history.bundle_count', $request->bundle_count);
        }
        $bundles = $query->orderBy('bundle_history.date')->get();
        return Inertia::render('StockBundleChart', [
            'bundles' => $bundles
        ]);
    }
    public function bundleDelete($id)
    {
        \DB::table('bundle_history')->where('id', $id)->delete();
        return redirect()->route('stock.bundle.chart')->with('success', 'Bundle deleted successfully!');
    }
    public function bundleBilla($id, $type)
    {
        $bundle = \DB::table('bundle_history')->where('id', $id)->first();
        $stock = \DB::table('finished_stocks')->where('id', $bundle->stock_id)->first();
        $order = \DB::table('orders')->where('lot', $stock->lot)->first();
        // Pass all data and bundle id to the view, but do not display unless needed in the view
        $data = [
            'bundle' => $bundle,
            'stock' => $stock,
            'order' => $order,
            'bundle_id' => $id
        ];
        if ($type === 'A') {
            return Inertia::render('BundleBillaA', $data);
        } elseif ($type === 'B') {
            return Inertia::render('BundleBillaB', $data);
        } else {
            return Inertia::render('BundleBillaC', $data);
        }
    }
    public function verifyBundle($id)
    {
        $bundle = \DB::table('bundle_history')->where('id', $id)->first();
        if (!$bundle) {
            return redirect()->back()->with('error', 'Bundle not found!');
        }
        $stock = \DB::table('finished_stocks')->where('id', $bundle->stock_id)->first();
        // Insert into verified_bundles
        \DB::table('verified_bundles')->insert([
            'stock_id' => $bundle->stock_id,
            'date' => $bundle->date,
            'sheets_per_bundle' => $bundle->sheets_per_bundle,
            'bundle_count' => $bundle->bundle_count,
            'packed_by' => $stock->packed_by ?? null,
            'party_name' => $stock->party_name ?? null,
            'lot' => $stock->lot ?? null,
            'cutsheet' => $stock->sheet_size ?? null,
            'peice' => $stock->khana ?? null,
            'widht' => $stock->b_width ?? null,
            'jalilenght' => $stock->jalilenght ?? null,
            'machine_id' => $stock->machine_id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Remove from bundle_history
        \DB::table('bundle_history')->where('id', $id)->delete();
        return redirect()->route('stock.bundle.chart')->with('success', 'Bundle verified and moved!');
    }
    public function verifiedBundles(Request $request)
    {
        $query = \DB::table('verified_bundles');
        if ($request->filled('party_name')) {
            $query->where('party_name', 'like', '%' . $request->party_name . '%');
        }
        if ($request->filled('lot')) {
            $query->where('lot', 'like', '%' . $request->lot . '%');
        }
        if ($request->filled('cutsheet')) {
            $query->where('cutsheet', 'like', '%' . $request->cutsheet . '%');
        }
        if ($request->filled('peice')) {
            $query->where('peice', 'like', '%' . $request->peice . '%');
        }
        if ($request->filled('widht')) {
            $query->where('widht', 'like', '%' . $request->widht . '%');
        }
        if ($request->filled('jalilenght')) {
            $query->where('jalilenght', 'like', '%' . $request->jalilenght . '%');
        }
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        if ($request->filled('sheets_per_bundle')) {
            $query->where('sheets_per_bundle', $request->sheets_per_bundle);
        }
        if ($request->filled('bundle_count')) {
            $query->where('bundle_count', $request->bundle_count);
        }
        if ($request->filled('packed_by')) {
            $query->where('packed_by', 'like', '%' . $request->packed_by . '%');
        }
        $bundles = $query->orderBy('date', 'desc')->get();
        return Inertia::render('VerifiedBundles', [
            'bundles' => $bundles
        ]);
    }
    public function deleteVerifiedBundle($id)
    {
        \DB::table('verified_bundles')->where('id', $id)->delete();
        return redirect()->route('verified.bundles')->with('success', 'Verified bundle deleted successfully!');
    }
}
