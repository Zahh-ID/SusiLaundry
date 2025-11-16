<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/orders",
     *     summary="Display a listing of the orders.",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index()
    {
        return Order::with(['customer', 'package'])->get();
    }

    /**
     * @OA\Get(
     *     path="/admin/orders/{order}",
     *     summary="Display the specified order.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="order",
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
    public function show(Order $order)
    {
        return $order->load(['customer', 'package']);
    }

    /**
     * @OA\Put(
     *     path="/admin/orders/{order}",
     *     summary="Update the specified order in storage.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="actual_weight", type="number"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="total_price", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'actual_weight' => 'sometimes|numeric',
            'status' => 'sometimes|string',
            'total_price' => 'sometimes|numeric',
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    /**
     * @OA\Delete(
     *     path="/admin/orders/{order}",
     *     summary="Remove the specified order from storage.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="order",
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
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
