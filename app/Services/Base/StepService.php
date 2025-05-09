<?php
namespace App\Services\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class StepService
{
    abstract public static function create(Request $request): JsonResponse;
}
