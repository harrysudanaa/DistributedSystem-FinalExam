<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenditureResource;
use App\Models\Expenditure;
use Illuminate\Http\Request;


class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenditures = Expenditure::with('creator')->get();
        return ExpenditureResource::collection($expenditures);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'book_name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'supplier' => 'required'
        ]);

        $request['total_price'] = $request->price * $request->quantity;
        $request['user_id'] = auth()->user()->id;

        $expenditure = Expenditure::create($request->all());

        $expenditureResource = new ExpenditureResource($expenditure->loadMissing('creator:id,name'));

        return response()->json([
            'success' => true,
            'message' => 'Data added successfully',
            'data' => $expenditureResource
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date',
            'book_name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'supplier' => 'required'
        ]);
        $request['total_price'] = $request->price * $request->quantity;
        $request['user_id'] = auth()->user()->id;

        $expenditure = Expenditure::findOrFail($id);
        $expenditure->update($request->all());

        $expenditureResource = new ExpenditureResource($expenditure->loadMissing('creator:id,name'));

        return response()->json([
            'success' => true,
            'message' => 'Data updated successfully',
            'data' => $expenditureResource
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenditure = Expenditure::findOrFail($id);
        $expenditure->delete();

        $expenditureResource = new ExpenditureResource($expenditure->loadMissing('creator:id,name'));

        return response()->json([
            'success' => true,
            'message' => 'Data delated successfully',
            'data' => $expenditureResource
        ], 201);
    }
}
