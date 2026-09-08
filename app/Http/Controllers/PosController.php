<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\SessionOrder;
use App\Models\Shift;
use App\Models\Tv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search', '');

        $query = Product::query();

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->orderBy('name')->get();

        $activeSessions = PlaySession::with('tv')
            ->where('status', 'active')
            ->get();

        return view('admin.pos.index', compact('products', 'activeSessions', 'category', 'search'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|in:session,direct',
            'play_session_id' => 'required_if:target_type,session|nullable|exists:play_sessions,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:cash,qris,transfer,other',
        ]);

        DB::transaction(function () use ($validated) {
            $totalAmount = 0;

            if ($validated['target_type'] === 'session') {
                $session = PlaySession::findOrFail($validated['play_session_id']);

                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product && $item['quantity'] > 0) {
                        $subtotal = $product->price * $item['quantity'];
                        $totalAmount += $subtotal;

                        if ($product->stock >= $item['quantity']) {
                            $product->decrement('stock', $item['quantity']);
                        }

                        SessionOrder::create([
                            'play_session_id' => $session->id,
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'subtotal' => $subtotal,
                        ]);
                    }
                }

                $newFnbAmount = $session->fnb_amount + $totalAmount;
                $session->update([
                    'fnb_amount' => $newFnbAmount,
                    'total_amount' => $session->rental_amount + $newFnbAmount,
                ]);
            } else {
                // Direct sale without PS session
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product && $item['quantity'] > 0) {
                        $subtotal = $product->price * $item['quantity'];
                        $totalAmount += $subtotal;

                        if ($product->stock >= $item['quantity']) {
                            $product->decrement('stock', $item['quantity']);
                        }
                    }
                }

                // Add to active shift
                $activeShift = Shift::where('status', 'active')->latest()->first();
                if ($activeShift) {
                    $activeShift->increment('total_revenue', $totalAmount);
                    $activeShift->increment('transactions_count', 1);
                }
            }
        });

        return back()->with('success', 'Pesanan F&B berhasil diproses!');
    }

    public function manage(Request $request)
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        return view('admin.pos.manage', compact('products'));
    }

    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'category' => 'required|in:food,drink,snack',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->boolean('is_available', true);

        Product::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            $validated
        );

        return back()->with('success', 'Produk berhasil disimpan!');
    }

    public function toggleProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_available' => !$product->is_available]);

        return back()->with('success', 'Status produk ' . $product->name . ' diperbarui.');
    }
}
