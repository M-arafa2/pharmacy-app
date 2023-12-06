<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaCreateRequest;
use App\Http\Resources\AreaResource;
use App\Models\area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiJsonResponse;

class areaController extends Controller
{
    use ApiJsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $areas = area::all();
            return Datatables::of($areas)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                // Update Button
                $updateButton = "<button class='btn btn-sm btn-info updateUser mx-1' ' data-id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";

                // Delete Button
                $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row->id . "'><i class='fa-solid fa-trash'></i></button>";

                return "<div style='display:flex;' class='justify-content-center'>" . $updateButton . " " . $deleteButton . "</div>";

            })
            ->make();

        } else {
            return view('areas.index');
        }
    }
    public function store(AreaCreateRequest $request)
    {
        if(area::create($request->validated())) {
            return $this->success("User Created Successfully", [], 201);
        } else {
            return $this->failure("invalid input", );
        }
    }
    // Read Employee record by ID
    public function show(area $area)
    {
        if($area) {
            return $this->success('', AreaResource::make($area));

        } else {
            return $this->failure('invalid id', 404);
        }
    }

    public function update(AreaCreateRequest $request, area $area)
    {
        if($area) {
            if($area->update($request->validated())) {
                return $this->success('Updated Successfully', []);
            } else {
                return $this->failure('invalid input');
            }
        } else {
            return $this->failure('invalid id', 404);
        }

    }

    public function delete(area $area)
    {
        if($area->delete()) {
            return $this->success('Deleted Successfully', []);
        } else {
            return $this->failure('invalid id', 404);
        }
    }
}
