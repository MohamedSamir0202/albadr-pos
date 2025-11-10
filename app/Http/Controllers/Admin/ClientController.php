<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientStatuses = ClientStatusEnum::labels();
        return view('admin.clients.create', compact('clientStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        Client::create($request->validated());
        session()->flash('success', 'Client created successfully.');
        return redirect()->route('admin.clients.index');
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $accountService = new \App\Services\ClientAccountService();

        $transactions = $accountService->getTransactions($client, [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ]);

        $totals = $accountService->calculateTotals($transactions);

        return view('admin.clients.show', compact('client', 'transactions', 'totals'));
    }



    public function search(Request $request)
    {
        $query = $request->input('query');

        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->paginate(10);

        return view('admin.clients.index', compact('clients', 'query'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        $clientStatuses = ClientStatusEnum::labels();
        return view('admin.clients.edit', compact('client', 'clientStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, string $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());
        session()->flash('success', 'Client updated successfully.');
        return redirect()->route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
        {
            $item = Client::findOrFail($id);
            $item->delete();

            return redirect()
                ->route('admin.clients.index')
                ->with('success', 'Item deleted successfully.');
        }
}
