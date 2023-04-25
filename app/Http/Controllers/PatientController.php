<?php

namespace App\Http\Controllers;


use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\PatientValidator;
use App\Http\Resources\PatientResource;

class PatientController extends Controller
{
    public function index()
    {
        try {
            $patients = Patient::with('visits')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Patients retrieved successfully',
                'data' => PatientResource::collection($patients)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve patients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showId(Request $request)
    {
        try {
            // Load the patient and eager load the related appointments
            $patient = Patient::findOrFail($request->input('id'));

            // Return a success response with the patient data
            return response()->json([
                'status' => 'success',
                'message' => 'Patient retrieved successfully',
                'data' => new PatientResource($patient)
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if the patient is not found or any other exception is thrown
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve patient data: ' . $e->getMessage()
            ], 404);
        }
    }

    public function showName(Request $request)
    {
        try {
            // Get the name query parameter from the request
            $name = $request->input('name');

            // Load the patient with the matching name and eager load the related appointments
            $patients = Patient::where('name', 'like', '%' . $name . '%')->get();

            // Return a success response with the patient data
            return response()->json([
                'status' => 'success',
                'message' => 'Patient retrieved successfully',
                'data' => PatientResource::collection($patients)
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if the patient is not found or any other exception is thrown
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve patient data: ' . $e->getMessage()
            ], 404);
        }
    }


    public function showDate(Request $request)
    {
        try {
            $startDate = $request->input('startDate') . ' 00:00:00';
            $endDate = $request->input('endDate') . ' 23:59:59';

            $patients = Patient::whereBetween('created_at', [$startDate, $endDate])
                ->get();
            
            // Return a success response with the list of patients
            return response()->json([
                'status' => 'success',
                'message' => 'Patients retrieved successfully',
                'data' => PatientResource::collection($patients)
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if an exception is caught
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve patients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = new PatientValidator();
        try {
            $validatedData = $request->validate($validator->rules());

            // Create the patient using the validated data
            $patient = Patient::create($validatedData);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Patient created successfully',
                'data' => new PatientResource($patient)
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = new PatientValidator();
        try {
            $patient = Patient::findOrFail($id);

            $validatedData = $request->validate($validator->rules());

            // Update the patient using the validated data
            $patient->update($validatedData);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Patient updated successfully',
                'data' => new PatientResource($patient)
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {

            $patient = Patient::findOrFail($request->input('id'));
            // Delete the patient
            $patient->delete();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Patient deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
