<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/packages",
     *     summary="Display a listing of the packages.",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index()
    {
        return Package::all();
    }

    /**
     * @OA\Post(
     *     path="/admin/packages",
     *     summary="Store a newly created package in storage.",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"package_name","description","price_per_kg"},
     *             @OA\Property(property="package_name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price_per_kg", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string',
            'description' => 'required|string',
            'price_per_kg' => 'required|numeric',
        ]);

        $package = Package::create($request->all());

        return response()->json($package, 201);
    }

    /**
     * @OA\Get(
     *     path="/admin/packages/{package}",
     *     summary="Display the specified package.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="package",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function show(Package $package)
    {
        return $package;
    }

    /**
     * @OA\Put(
     *     path="/admin/packages/{package}",
     *     summary="Update the specified package in storage.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="package",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="package_name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price_per_kg", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'package_name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'price_per_kg' => 'sometimes|numeric',
        ]);

        $package->update($request->all());

        return response()->json($package);
    }

    /**
     * @OA\Delete(
     *     path="/admin/packages/{package}",
     *     summary="Remove the specified package from storage.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="package",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     )
     * )
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json(null, 204);
    }
}
