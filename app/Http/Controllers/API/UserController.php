<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use App\Http\Resources\Users as UsersResource;

class UserController extends BaseController
{

    /**
     * Get all non admin users list
     *
     * @return Response
     */
    public function index()
    {
        try {
            $users = User::where('is_admin', 0)->orderBy('created_at', 'desc')->get();
            if ($users->count() === 0) {
                return $this->errorResponse(UsersResource::collection($users), Config::get('constants.api.no_data_found'));
            }
            return $this->successResponse(UsersResource::collection($users), Config::get('constants.api.success.user.get_all'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    public function approved(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'approved' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.user.approved_validation'), $validator->errors(), 422);
        }
        try {
            $approved = $request->approved ? 1 : 0;
            $user = User::where('id', $request->user_id)->where('is_admin', 0)->update(['approved' => $approved]);
            if (!$user) {
                return $this->errorResponse(Config::get('constants.api.error.user.approved'));
            }
            return $this->successResponse($user, Config::get('constants.api.success.user.approved'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }
}
