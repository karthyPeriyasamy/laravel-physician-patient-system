<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointments;
use App\Http\Controllers\API\BaseController;

class AppointmentController extends BaseController
{
    /**
     * Create a new appointment.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'specialist_id' => 'required|numeric',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), $validator->errors(), 422);
        }
        try {
            $appointment = Appointments::create([
                'title' => $request->title,
                'user_id' => $request->user()->id,
                'specialist_id' => $request->specialist_id,
                'description' => $request->description,
                'status' => 1
            ]);
            return $this->successResponse($appointment, Config::get('constants.api.success.appointments.create'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * Update a  appointment.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|numeric',
            'title' => 'required|string',
            'specialist_id' => 'required|numeric',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), $validator->errors(), 422);
        }
        try {
            $appointment = Appointments::find($request->appointment_id);
            if (!$appointment) {
                return $this->errorResponse(Config::get('constants.api.error.appointments.update'), [], 422);
            }
            $appointment->title = $request->title;
            $appointment->specialist_id = $request->specialist_id;
            $appointment->description = $request->description;
            $appointment->save();
            return $this->successResponse($appointment, Config::get('constants.api.success.appointments.update'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * delete a appointment.
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), $validator->errors(), 422);
        }
        try {
            $appointment = Appointments::find($request->appointment_id);
            if (!$appointment) {
                return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), [], 422);
            }
            $delete = $appointment->delete();
            if (!$delete) {
                return $this->errorResponse(Config::get('constants.api.error.appointments.delete'), [], 422);
            }
            return $this->successResponse($delete, Config::get('constants.api.success.appointments.delete'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * Get a appointment details to Patient and Physician users.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), $validator->errors(), 422);
        }
        try {
            $appointment = Appointments::find($request->appointment_id);
            if (!$appointment) {
                return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), [], 422);
            }
            return $this->successResponse($appointment, Config::get('constants.api.success.appointments.get'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
     * Update a appointment status when a patient or physician request it.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|numeric',
            'status' => 'required|numeric|exists:appointment_statuses,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.appointments.validation'), $validator->errors(), 422);
        }
        try {
            $appointment = Appointments::find($request->appointment_id);
            if (!$appointment) {
                return $this->errorResponse(Config::get('constants.api.error.appointments.update'), [], 422);
            }
            $appointment->status = $request->status;
            $appointment->save();
            return $this->successResponse($appointment, Config::get('constants.api.success.appointments.update'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
    * Get all appointments to a patient user.
    *
    * @param  Request  $data
    * @return Response
    */
    public function patientAppointments(Request $request)
    {
        try {
            $appointments = Appointments::where([
                'user_id' => $request->user()->id
            ])->orderBy('created_at', 'desc')->get();
            if ($appointments->count() === 0) {
                return $this->errorResponse([], Config::get('constants.api.no_data_found'));
            }
            return $this->successResponse($appointments, Config::get('constants.api.success.appointments.get_all'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
    * Get all appointments to a physician user.
    *
    * @param  Request  $data
    * @return Response
    */
    public function physicianAppointments(Request $request)
    {
        try {
            $appointments = DB::table('users AS u')
            ->join('specialists_user AS su', 'su.user_id', '=', 'u.id')
            ->join('appointments AS a', 'a.specialist_id', '=', 'su.specialists_id')
            ->select('a.*')
            ->where('u.id', '=', $request->user()->id)
            ->get();

            if ($appointments->count() === 0) {
                return $this->errorResponse($appointments, Config::get('constants.api.no_data_found'));
            }
            return $this->successResponse($appointments, Config::get('constants.api.success.appointments.get_all'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }
}
