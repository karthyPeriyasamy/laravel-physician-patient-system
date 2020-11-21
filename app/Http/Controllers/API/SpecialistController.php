<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Specialists;
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
        $specialists = Specialists::where('status', 1)->orderBy('created_at', 'desc')->get();
        if ($specialists->count() === 0) {
            return $this->successResponse(SpecialistsResource::collection($specialists), Config::get('constants.api.no_data_found'));
        }
        return $this->successResponse(SpecialistsResource::collection($specialists), Config::get('constants.api.success.specialists.get_all'));
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
            return $this->errorResponse($validator->errors(), Config::get('constants.api.error.specialists.validation'));
        }
        $specialists = Specialists::create([
            'name' => $request->name,
            'status' => 1
        ]);
        return $this->successResponse($specialists, Config::get('constants.api.success.specialists.create'));
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
            return $this->errorResponse($validator->errors(), Config::get('constants.api.error.specialists.validation'));
        }
        $specialists = Specialists::where('id', $request->specialist_id)
                        ->update(['name' => $request->name]);
        if (!$specialists) {
            return $this->errorResponse($validator->errors(), Config::get('constants.api.error.specialists.update'));
        }
        return $this->successResponse($specialists, Config::get('constants.api.success.specialists.update'));
    }
}
