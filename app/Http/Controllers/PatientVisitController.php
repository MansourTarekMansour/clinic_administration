<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Http\Request;
use App\Http\Requests\VisitValidator;
use App\Http\Resources\VisitResource;


class PatientVisitController extends Controller
{

    public function index()
    {
        try {
            // Load the visits for the patient
            $visits = PatientVisit::all();

            // Return a success response with the visits data
            return response()->json([
                'status' => 'success',
                'message' => 'Visits retrieved successfully',
                'data' => VisitResource::collection($visits)
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if any exception is thrown
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve visits data: ' . $e->getMessage()
            ], 404);
        }
    }


    public function store(Request $request, $patientId)
    {
        $validator = new VisitValidator();

        try {
            // Validate the request data
            $validatedData = $request->validate($validator->rules());

            // Find the patient by id
            $patient = Patient::findOrFail($patientId);
            $visit = new PatientVisit;
            $visit->fill($validatedData);
            $visit->total = $visit->cost - $visit->discount;
            $patient->visits()->save($visit);
            

            // Return a success response with the visit data
            return response()->json([
                'status' => 'success',
                'message' => 'Visit created successfully',
                'data' => new VisitResource($visit)
            ], 201);
        } catch (\Exception $e) {
            // Return an error response if any exception is thrown
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create visit: ' . $e->getMessage()
            ], 400);
        }
    }



    public function showId(Request $request)
    {
        try {
            // Load the visit for the patient
            $visit = PatientVisit::findOrFail($request->input('id'));

            // Return a success response with the visit data
            return response()->json([
                'status' => 'success',
                'message' => 'Visit retrieved successfully',
                'data' => new VisitResource($visit)
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if the visit is not found or any other exception is thrown
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve visit data: ' . $e->getMessage()
            ], 404);
        }
    }

    public function showName(Request $request)
    {
        try {
            // Get the name query parameter from the request
            $name = $request->input('name');

            // Load the patient with the matching name and eager load the related appointments
            $visits = PatientVisit::where('name', 'like', '%' . $name . '%')->get();

            // Return a success response with the patient data
            return response()->json([
                'status' => 'success',
                'message' => 'Patient retrieved successfully',
                'data' => VisitResource::collection($visits)
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

            $visits = PatientVisit::whereBetween('created_at', [$startDate, $endDate])
                ->get();

            // Return a success response with the list of patients
            return response()->json([
                'status' => 'success',
                'message' => 'Patients retrieved successfully',
                'data' => VisitResource::collection($visits)
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

    public function update(Request $request, $id)
    {
        $validator = new VisitValidator();
        try {
            $visit = PatientVisit::findOrFail($id);

            $validatedData = $request->validate($validator->rules());

            // Update the patient using the validated data
            $visit->update($validatedData);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Patient updated successfully',
                'data' => new VisitResource($visit)
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

            $visit = PatientVisit::findOrFail($request->input('id'));
            // Delete the patient
            $visit->delete();

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
