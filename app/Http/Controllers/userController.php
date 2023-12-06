<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Resources\UserResource;
use App\Models\doctor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiJsonResponse;

class userController extends Controller
{
    use ApiJsonResponse;
    /**
     *
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $users = User::all();
            return Datatables::of($users)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $image = "<img style='width:50px;' src='/" . $row->image . "' >";
                return $image;
            })
            ->addColumn('action', function ($row) {
                // Update Button
                $updateButton = "<button class='btn btn-sm btn-info updateUser mx-1' ' data-id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";

                // Delete Button
                $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row->id . "'><i class='fa-solid fa-trash'></i></button>";

                return "<div style='display:flex;' class='justify-content-center'>" . $updateButton . " " . $deleteButton . "</div>";

            })
            ->rawColumns(['image', 'action'])
            ->make();
        } else {

            return view('users.index');
        }
    }

    public function store(UserCreateRequest $request)
    {

        $validated = $request->validated();
        $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
        $validated['image'] = $image_path;

        if(User::create($validated)) {
            //$user->sendEmailVerificationNotification();
            return $this->success("User Created Successfully", [], 201);


        } else {
            return $this->failure("invalid input", );
        }

    }
    // Read Employee record by ID
    public function show(User $user)
    {

        ## Read POST data
        if($user) {
            return $this->success('', UserResource::make($user));

        } else {
            return $this->failure('invalid id', 404);
        }


    }

    // Update Employee record
    public function update(UserEditRequest $request, User $user)
    {
        if($user) {
            $validated = $request->validated();

            if($request->file('image')) {
                $image_path = $request->file('image')->store('/images/resource', ['disk' =>   'my_images']);
                $validated['image'] = $image_path;
            }

            if($request->password) {
                $validated['password'] = Hash::make($request->post('password'));

            }
            if($user->update($validated)) {
                return $this->success('Updated Successfully', []);
            } else {
                return $this->failure('invalid input');
            }
        } else {
            return $this->failure('invalid id', 404);
        }


    }

    public function delete(User $user)
    {

        if($user->delete()) {
            return $this->success('Deleted Successfully', []);
        } else {
            return $this->failure('invalid id', 404);
        }

    }


}
