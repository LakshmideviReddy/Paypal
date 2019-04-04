<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
class APIController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(5);
        return response(array(
                'error' => false,
                'products' =>$products->toArray(),
               ),200);
    }
    public function store(Request $request)
    {
        Product::create($request->all());
        return response(array(
                'error' => false,
                'message' =>'Product created successfully',
               ),200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        return response(array(
                'error' => false,
                'product' =>$product,
               ),200);
    }
    public function update(Request $request, $id)
    {
        Product::find($id)->update($request->all());
        return response(array(
                'error' => false,
                'message' =>'Product updated successfully',
               ),200);
    }
    public function destroy($id)
    {
        Product::find($id)->delete();
        return response(array(
                'error' => false,
                'message' =>'Product deleted successfully',
               ),200);
    }
}
?>
