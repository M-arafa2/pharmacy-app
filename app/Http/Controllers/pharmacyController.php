<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacyCreateRequest;
use App\Http\Requests\PharmacyEditRequest;
use App\Http\Resources\PharmacyResource;
use App\Models\doctor;
use App\Models\staff;
use App\Models\pharmacy;
use App\Models\area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiJsonResponse;

class pharmacyController extends Controller
{
    use ApiJsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $pharmacies = pharmacy::with('staff')->with('area')->get();
            return Datatables::of($pharmacies)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $image = "<img style='width:50px;' src='/" . $row->staff->image . "' >";
                    return $image;
                })
                ->addColumn('action', function ($row) {
                    // Update Button
                    $updateButton = "<button class='btn btn-sm btn-info updateUser mx-1' data-role ='" . Auth::user()->role . "' data-id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";

                    // Delete Button
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row->id . "'><i class='fa-solid fa-trash'></i></button>";

                    return "<div style='display:flex;' class='justify-content-center'>" . $updateButton . " " . $deleteButton . "</div>";

                })
                ->rawColumns(['image', 'action'])
                ->make();
        } else {
            $areas = area::select('*')->get();

            return view('pharmacies.index', compact('areas'));
        }

    }

    public function store(PharmacyCreateRequest $request)
    {
        $validated = $request->validated();
        $path = $request->file('image')->store('/images/doctors', ['disk' =>   'my_images']);
        $validated['image'] = $path;
        $staff = staff::create([...$validated,'role' => 'pharmacy'
        ]);
        $pharmacy = pharmacy::create([...$validated,'staff_id' => $staff->id
        ]);
        if($staff && $pharmacy) {
            return $this->success('Created Successfully', []);
        } else {
            return $this->failure('invalid input');
        }
    }

    public function show(pharmacy $pharmacy)
    {
        if($pharmacy) {
            return $this->success('', PharmacyResource::make($pharmacy));
        } else {
            return $this->failure('invalid id', 404);
        }
    }

    public function update(PharmacyEditRequest $request, pharmacy $pharmacy)
    {
        if($pharmacy) {
            $pharstaff = staff::find($pharmacy->staff_id);
            $validated = $request->validated();
            if($request->file('image')) {
                $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
                $validated['image'] = $image_path;
            }
            if($request->password) {
                $validated['password'] = Hash::make($request->post('password'));
            }
            if($pharstaff->update($validated) &&
                $pharmacy->update([...$validated,'staff_id' => $pharstaff->id])) {

                return $this->success('updated Successfully', []);
            } else {
                return $this->failure('invalid inputs');
            }
        } else {
            return $this->failure('invalid id');
        }
    }
    public function delete(pharmacy $pharmacy)
    {

        $dstaff = staff::find($pharmacy->staff_id);
        if($pharmacy->delete() && $dstaff->delete()) {
            return $this->success('Deleted Successfully', []);
        } else {
            return $this->failure('invalid id', 404);
        }

    }


}
