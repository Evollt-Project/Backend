<?php

namespace App\Http\Controllers\Api\V1\Article\Step;

use App\Enums\StepEnums;
use App\Http\Controllers\Controller;
use App\Services\Article\Step\TextService;
use App\Services\Base\StepService;
use Illuminate\Http\Request;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'lesson_id' => 'required|integer',
            'step_type' => 'required|integer'
        ]);
        $stepType = StepEnums::tryFrom($validatedRequest['step_type']);

        return match ($stepType) {
            StepEnums::TEXT => TextService::create($request),
            default => [
                'message' => 'Такой тип шага не найден'
            ]
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
