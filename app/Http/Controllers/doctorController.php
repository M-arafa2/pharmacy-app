<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorCreateRequest;
use App\Http\Resources\DoctorResource;
use App\Models\doctor;
use App\Models\staff;
use App\Models\pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiJsonResponse;

class doctorController extends Controller
{
    use ApiJsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = doctor::with('staff')->with('pharmacy.staff');
            if(Auth::user()->role == 'pharmacy') {
                $phar = pharmacy::where('staff_id', Auth::user()->id)->first();
                $query->where('pharmacy_id', $phar->id);
            }
            $doctors = $query->get();

            return Datatables::of($doctors)
            ->addIndexColumn()
            ->addColumn('is_banned', function ($row) {

                if($row->is_banned == 1) {
                    return "Banned";
                } else {
                    return "Not Banned";
                }

            })
            ->addColumn('image', function ($row) {
                $image = "<img style='width:50px;' src='/" . $row->staff->image . "' >";
                return $image;
            })
            ->addColumn('action', function ($row) {
                //banned
                if($row->is_banned) {
                    $banButton = "<button class='btn btn-sm btn-danger banUser'   data-id='" . $row->id . "' > UNBAN</button>";
                } else {
                    $banButton = "<button class='btn btn-sm btn-danger banUser'  data-id='" . $row->id . "' > BAN</button>";
                }



                // Update Button
                $updateButton = "<button class='btn btn-sm btn-info updateUser mx-1' data-role ='" . Auth::user()->role . "' data-id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";

                // Delete Button
                $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row->id . "'><i class='fa-solid fa-trash'></i></button>";

                return "<div style='display:flex;' class='justify-content-center'>" . $banButton . " " . $updateButton . " " . $deleteButton . "</div>";

            })
            ->rawColumns(['image', 'action'])
            ->make();

        } else {
            if(Auth::user()->role == 'admin') {
                $pharmacies = pharmacy::select('id', 'staff_id')->with('staff:id,name')->get();
            } elseif(Auth::user()->role == 'pharmacy') {
                $pharmacies = pharmacy::select('id', 'staff_id')->with('staff:id,name')->where('staff_id', Auth::user()->id)->get();

            }


            return view('doctors.index', compact('pharmacies'));
        }

    }


    public function store(DoctorCreateRequest $request)
    {
        $path = $request->file('image')->store('/images/doctors', ['disk' =>   'my_images']);
        $validated['image'] = $path;
        $staff = staff::create([
            ...$request->validated(),
            'role' => 'doctor'
        ]);
        if(Auth::user()->role == 'pharmacy') {
            $pharmacy = pharmacy::where('staff_id', Auth::user()->id)->first();
            $pharmacy_id = $pharmacy->id;
        } elseif (Auth::user()->role == 'admin') {
            $pharmacy_id = $request->pharmacy_id;
        }
        $doctor = doctor::create(['pharmacy_id' => $pharmacy_id,
            'staff_id' => $staff->id
        ]);
        if($staff && $doctor) {
            return $this->success('Created Successfully', []);
        } else {
            return $this->failure('invalid input');
        }
    }
    // Read Employee record by ID
    public function show(doctor $doctor)
    {
        if($doctor) {
            return $this->success('', DoctorResource::make($doctor));
        } else {
            return $this->failure('invalid id', 404);
        }

    }

    // Update Employee record
    public function update(DoctorCreateRequest $request, doctor $doctor)
    {
        if($doctor) {
            $validated = $request->validated();
            $docstaff = staff::find($doctor->staff_id);
            if($request->file('image')) {
                $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
                $validated['image'] = $image_path;
            }
            if($request->password) {
                $validated['password'] = Hash::make($request->post('password'));
            }
            if(Auth::user()->role == 'admin') {
                if($docstaff->update($validated) && $doctor->update($validated)) {
                    return $this->success('updated Successfully', []);
                } else {
                    return $this->failure('invalid inputs');
                }
            } elseif(Auth::user()->role == 'pharmacy') {
                if($docstaff->update($validated)) {
                    return $this->success('updated Successfully', []);
                } else {
                    return $this->failure('invalid inputs');

                }
            }
        } else {
            return $this->failure('invalid id');
        }
    }

    // Delete Employee
    public function delete(doctor $doctor)
    {
        $dstaff = staff::find($doctor->staff_id);

        if($doctor->delete() && $dstaff->delete()) {
            return $this->success('Deleted Successfully', []);
        } else {
            return $this->failure('invalid id', 404);
        }
    }
    public function ban(Request $request)
    {
        $docdata = doctor::find($request->id);
        if($docdata->is_banned == 1) {
            if($docdata->update(['is_banned' => 0])) {
                return $this->success('BANNED Successfully', []);
            } else {
                return $this->failure('');
            }

        } elseif($docdata->is_banned == 0) {
            if($docdata->update(['is_banned' => 1])) {
                return $this->success('UNBANNED Successfully', []);
            } else {
                return $this->failure('');
            }

        }

    }


}
