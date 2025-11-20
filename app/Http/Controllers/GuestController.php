<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/paket",
     *     summary="Display a listing of the packages.",
     *     tags={"Guest"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function showPackages()
    {
        return Package::all();
    }

    /**
     * @OA\Post(
     *     path="/order/store",
     *     summary="Store a newly created order in storage.",
     *     tags={"Guest"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","address","package_id","estimated_weight","service_type"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="package_id", type="integer"),
     *             @OA\Property(property="estimated_weight", type="number"),
     *             @OA\Property(property="service_type", type="string"),
     *             @OA\Property(property="notes", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation"
     *     )
     * )
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric',
            'service_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $customerPayload = [
            'name' => $request->name,
            'phone' => '',
            'address' => $request->address,
        ];

        if (Schema::hasColumn('customers', 'email')) {
            $customerPayload['email'] = $request->email;
        }

        $customer = Customer::create($customerPayload);

        $order = Order::create([
            'order_code' => Str::random(10),
            'customer_id' => $customer->id,
            'package_id' => $request->package_id,
            'estimated_weight' => $request->estimated_weight,
            'service_type' => $request->service_type,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return response()->json($order, 201);
    }

    /**
     * @OA\Get(
     *     path="/tracking/{kode}",
     *     summary="Track an order by its code.",
     *     tags={"Guest"},
     *     @OA\Parameter(
     *         name="kode",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function trackOrder($kode)
    {
        return Order::where('order_code', $kode)->firstOrFail();
    }
}
