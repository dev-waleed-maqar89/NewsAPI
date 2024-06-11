<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\APi\V1\AdminRequest;
use App\Http\Resources\Main\AdminResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Admin;
use Illuminate\Http\Request;

class ApiAdminController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $admins = AdminResource::collection(Admin::paginate(3));
        $page = request()->page ?? 1;
        return $this->apiSuccess(compact('page', 'admins'));
    }

    public function store(AdminRequest $request)
    {
        $admin = Admin::create([
            'user_id' => $request->user_id,
            'role' => $request->role
        ]);
        $msg = 'User was added to admins tabl';
        $admin = new AdminResource($admin);
        return $this->apiSuccess(compact('admin'), $msg);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $admin = new AdminResource($admin);
            return $this->apiSuccess(compact('admin'));
        } else {
            $msg = 'No admin for such ID';
            return $this->apiError($msg, 404);
        }
    }
    public function update(Request $request, $id)
    {
        $roles = 'editor,moderator,supervisor';
        $request->validate([
            'role' => ['required', 'in:' . $roles],
        ]);
        $admin = Admin::find($id);
        if ($admin) {
            $admin->update(['role' => $request->role]);
            $msg = 'Admin role has been changed to ' . $request->role;
            $admin = new AdminResource($admin);
            return $this->apiSuccess(compact('admin'), $msg);
        } else {
            $msg = 'No admin for such ID';
            return $this->apiError($msg, 404);
        }
    }
    public function destroy($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $admin->delete();
            $msg = 'User has been removed from admins table';
            return $this->apiSuccess([], $msg);
        } else {
            $msg = 'No admin for such ID';
            return $this->apiError($msg, 404);
        }
    }
}