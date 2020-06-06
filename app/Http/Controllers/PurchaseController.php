<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Products;
use App\Providers;
use App\Purchases;
use App\Purchases_item;
use App\Traits\KardexTrait;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PurchaseController extends Controller
{

    use KardexTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \view('purchases');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::select(['id', 'name'])->where('is_available', 1)->get();
        $providers = Providers::select(['id', 'name'])->where('is_available', 1)->get();
        //->where('is_available', 1);
        return view('purchases.add', compact(['categories', 'providers']));
    }

    /**
     * Get product List
     *
     * @return \Illuminate\Http\Response
     */
    public function GetList()
    {
        $list = Products::select(['id', 'name', 'quantity', 'purchase', 'price1'])->get();

        return Datatables::of($list)
            ->addColumn('action', '<div style="display: inline-flex">
            <button class="btn btn-primary btn-sm mr-1" onclick="add({{ $id }})"><i class="fas fa-plus"></i>Agregar</button>
            </div>')
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //invoice headers info
            $purchase = new Purchases;
            $purchase->provider_id = $request->provider;
            $purchase->quantity = $request->grandquantityvalue;
            $purchase->total = $request->grandtotalvalue;
            $purchase->subtotal = $request->grandtotalvalue;
            $purchase->is_deleted = 0;

            if ($purchase->save()) {

                $id = $purchase->id;
                $counter = $request->trCount;

                for ($i = 1; $i <= $counter; $i++) {

                    $purchaseitem = new Purchases_item;
                    //database and request handlers
                    $data = ['status', 'quantityvalue', 'purchasevalue', 'totalvalue'];
                    $db = ['status', 'quantity', 'unit_price', 'total'];

                    for ($j = 0; $j < 4; $j++) {
                        //Packing item data to -> $purchaseitem
                        $modifier = $data[$j] . "" . $i;
                        $dbmodifier = $db[$j];
                        $purchaseitem->$dbmodifier = $request->$modifier;
                    }

                    $purchaseitem->purchase_id = $id;

                    if ($purchaseitem->status == "nuevo") {

                        $newProduct = new Products;
                        $newProduct->code = $request->{'pcodevalue' . $i};
                        $newProduct->name = $request->{'pnamevalue' . $i};
                        $newProduct->image = "media/photo_default.png";
                        $newProduct->provider_id = $request->{'provider' . $i};
                        $newProduct->category_id = $request->{'category' . $i};
                        $newProduct->purchase = $request->{'purchasevalue' . $i};
                        $newProduct->quantity = $request->{'quantityvalue' . $i};
                        $newProduct->type = 1;
                        $newProduct->price1 = $request->{'price' . $i};
                        $newProduct->utility1 = $request->{'price' . $i}-$request->{'purchasevalue' . $i};
                        $newProduct->is_available = 0;
                        $newProduct->is_deleted = 0;
                        $newProduct->save();

                        $purchaseitem->product_id = $newProduct->id;

                        //Adding to Kardex
                        $this->storedata("new", $newProduct->id, $purchaseitem->quantity, $purchaseitem->unit_price, $purchaseitem->total);
                    } else {
                        $purchaseitem->product_id = $request->{'idvalue' . $i};
                        //Adding to Kardex
                        $this->storedata("add", $purchaseitem->product_id, $purchaseitem->quantity, $purchaseitem->unit_price, $purchaseitem->total);

                        //Update quantity
                        $product = Products::find($purchaseitem->product_id);
                        // Make sure we've got the Products model
                        if ($product) {
                            $product->quantity = ($product->quantity + $purchaseitem->quantity);
                            $product->save();
                        }
                    }

                    $purchaseitem->save();
                }

                return response()->json(['message' => 'Factura guardada']);

            }

            return response()->json(['message' => 'Ocurrió un error al registrar la información']);

        } catch (Exception $e) {

            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
