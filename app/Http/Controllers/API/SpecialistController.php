<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Specialists;
use App\Models\User;
use App\Http\Resources\Specialists as SpecialistsResource;
use App\Http\Controllers\API\BaseController;

class SpecialistController extends BaseController
{
    /**
     * Get all non admin users list
     *
     * @return Response
     */
    public function index()
    {
        try {
            $specialists = Specialists::where('status', 1)->orderBy('created_at', 'desc')->get();
            if ($specialists->count() === 0) {
                return $this->successResponse(SpecialistsResource::collection($specialists), Config::get('constants.api.no_data_found'));
            }
            return $this->successResponse(SpecialistsResource::collection($specialists), Config::get('constants.api.success.specialists.get_all'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * create a specialist
     *
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:specialists']
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.specialists.validation'), $validator->errors(), 422);
        }
        try {
            $specialists = Specialists::create([
                'name' => $request->name,
                'status' => 1
            ]);
            return $this->successResponse($specialists, Config::get('constants.api.success.specialists.create'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * update a specialist
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:specialists'],
            'specialist_id' => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.specialists.validation'), $validator->errors(), 422);
        }
        try {
            $specialists = Specialists::where('id', $request->specialist_id)->update(['name' => $request->name]);
            if (!$specialists) {
                return $this->errorResponse(Config::get('constants.api.error.specialists.update'));
            }
            return $this->successResponse($specialists, Config::get('constants.api.success.specialists.update'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * Add a specialist to a physician
     *
     * @param  Request $request
     * @return Response
     */
    public function addToPhysician(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Config::get('constants.api.error.specialists.validation'), 422);
        }
        try {
            $specialist = Specialists::find($request->specialist_id);
            if (!$specialist) {
                return $this->errorResponse($specialist, Config::get('constants.api.error.specialists.validation', 422));
            }
            $user = User::find($request->user()->id);
            $userHasSpecialist = $user->specialists()->where('id', $request->specialist_id)->exists();
            if ($userHasSpecialist) {
                return $this->errorResponse(Config::get('constants.api.error.specialists.exists'));
            }
            $user->specialists()->attach($request->specialist_id);
            return $this->successResponse([], Config::get('constants.api.success.specialists.update'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * Remove a specialist from a physician
     *
     * @param  Request $request
     * @return Response
     */
    public function removeFromPhysician(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specialist_id' => ['required', 'numeric'],

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.specialists.validation'), $validator->errors(), 422);
        }
        try {
            $specialist = Specialists::find($request->specialist_id);
            if (!$specialist) {
                return $this->errorResponse($specialist, Config::get('constants.api.error.specialists.validation'), 422);
            }
            $user = User::find($request->user()->id);
            $userHasSpecialist = $user->specialists()->where('id', $request->specialist_id)->exists();
            if (!$userHasSpecialist) {
                return $this->errorResponse(Config::get('constants.api.error.specialists.not_exists'));
            }
            $user->specialists()->detach($request->specialist_id);
            return $this->successResponse([], Config::get('constants.api.success.specialists.update'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }
}
