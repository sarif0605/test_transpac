<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkUnit\WorkUnitCreateRequest;
use App\Http\Requests\WorkUnit\WorkUnitUpdateRequest;
use App\Http\Resources\WorkUnit\WorkUnitResource;
use App\Http\Resources\WorkUnit\WorkUnitResourceCollection;
use App\Models\WorkUnits;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $genres = WorkUnits::orderBy('created_at', 'desc')->paginate(6, ['*'], 'page', $page);
        return (new WorkUnitResourceCollection($genres))->response()->setStatusCode(201);
    }

    public function getAll(){
        $data = WorkUnits::all();
        return (new WorkUnitResourceCollection($data))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkUnitCreateRequest $request)
    {
        $data = $request->validated();
        WorkUnits::create($data);
        return response()->json([
            'message' => 'Berhasil Menambahkan Work Unit',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = WorkUnits::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Work Unit dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        return (new WorkUnitResource($data))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkUnitUpdateRequest $request, string $id)
    {
        $work_units = WorkUnits::find($id);
        if (!$work_units) {
            return response()->json([
                'message' => 'Work Unit dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        $data = $request->validated();
        $work_units->update($data);
        return (new WorkUnitResource($work_units))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $work_units = WorkUnits::find($id);
        if (!$work_units) {
            return response()->json([
                'message' => 'Work Unit dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        $work_units->delete();
        return response()->json([
            'message' => 'Work Unit berhasil dihapus',
        ], 200);
    }
}
