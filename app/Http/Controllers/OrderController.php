<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new OrderCollection(Order::with('user')->with('products')->where('status', 0)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Almacenar orden
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->total = $request->total;
        $order->save();

        //Obtener el id de la orden
        $id = $order->id;

        //Obtener los productos de la orden
        $products = $request->products;

        //Formatear arreglo de productos
        $products_order = [];

        foreach ($products as $product) {
            $products_order[] = [
                'order_id' => $id,
                'product_id' => $product['id'],
                'amount' => $product['amount'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        //Almacenar productos de la orden
        OrderProduct::insert($products_order);

        return ['message' => 'Pedido realizado correctamente. Estará listo en unos minutos.'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order->status = 1;
        $order->save();

        return ['Pedido' => $order];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
