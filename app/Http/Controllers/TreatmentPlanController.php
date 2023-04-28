<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Models\TreatmentPlan;
use App\Models\MedicalProcedure;
use App\Http\Requests\TreatmentPlanRequest;
use App\Http\Requests\TreatmentPlanValidator;
use App\Http\Resources\TreatmentPlanResource;

class TreatmentPlanController extends Controller
{
    public function index()
    {
        try {
            $treatmentPlans = TreatmentPlan::with('medicalProcedures.types')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Treatment plans retrieved successfully',
                'data' => TreatmentPlanResource::collection($treatmentPlans),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve treatment plans: ' . $e->getMessage(),
            ], 404);
        }
    }

    public function store(TreatmentPlanRequest $request)
    {
        try {
            $treatmentPlan = new TreatmentPlan();
            $treatmentPlan->name = $request->input('name');
            $treatmentPlan->save();

            // attach medical procedures
            $medicalProcedures = $request->input('medical_procedures');
            foreach ($medicalProcedures as $mp) {

                $medicalProcedure = new MedicalProcedure();
                $medicalProcedure->name = $mp['name'];
                $treatmentPlan->medicalProcedures()->save($medicalProcedure);

                // attach types
                $types = $mp['types'];
                foreach ($types as $type) {
                    $t = new Type();
                    $t->name = $type['name'];
                    $t->price = $type['price'];
                    $medicalProcedure->types()->save($t);
                }
            }

            $treatmentPlan->load('medicalProcedures.types');

            return response()->json([
                'status' => 'success',
                'message' => 'Treatment plan created successfully',
                'data' => new TreatmentPlanResource($treatmentPlan),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create treatment plan: ' . $e->getMessage(),
            ], 400);
        }
    }


    public function update(TreatmentPlanRequest $request, $id)
    {
        $validatedData = $request->validated();

        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid ID parameter',
            ], 400);
        }

        try {
            $treatmentPlan = TreatmentPlan::findOrFail($id);
            $treatmentPlan->update($validatedData);

            // Sync medical procedures
            $medicalProcedures = $request->input('medical_procedures');
            if (!empty($medicalProcedures)) {
                $treatmentPlan->medicalProcedures()->delete();
                foreach ($medicalProcedures as $mp) {
                    $medicalProcedure = new MedicalProcedure(['name' => $mp['name']]);
                    $medicalProcedure = $treatmentPlan->medicalProcedures()->save($medicalProcedure);

                    // Sync types for each medical procedure
                    $types = $mp['types'];
                    foreach ($types as $type) {
                        $t = new Type([
                            'name' => $type['name'],
                            'price' => $type['price'],
                        ]);
                        $medicalProcedure->types()->save($t);
                    }
                }
            } else {
                $treatmentPlan->medicalProcedures()->delete();
            }

            $treatmentPlan->load('medicalProcedures.types');

            return response()->json([
                'status' => 'success',
                'message' => 'Treatment plan updated successfully',
                'data' => new TreatmentPlanResource($treatmentPlan),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update treatment plan: ' . $e->getMessage(),
            ], 400);
        }
    }




    public function destroy($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid ID parameter',
            ], 400);
        }

        try {
            $treatmentPlan = TreatmentPlan::findOrFail($id);
            $treatmentPlan->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Treatment plan deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete treatment plan: ' . $e->getMessage(),
            ], 400);
        }
    }
}
