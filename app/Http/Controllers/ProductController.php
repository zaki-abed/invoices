<?php

namespace App\Http\Controllers;

use App\Product;
use App\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.product', compact('products', 'sections'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_name' => 'required',
            'section_id' => 'required',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id
        ]);
        session()->flash('add_product', 'تم إضافة المنتج بنجاح');
        return redirect('/products');
    }

    public function update(Request $request)
    {
        $id = Section::where('section_name', $request->section_name)->first()->id;

       $products = Product::findOrFail($request->pro_id);

       $products->update([
       'product_name' => $request->product_name,
       'description' => $request->description,
       'section_id' => $id,
       ]);

       session()->flash('update_section_success', 'تم تعديل المنتج بنجاح');
       return back();
    }

    public function destroy(Request $request)
    {
        $products = Product::findOrFail($request->pro_id);
         $products->delete();
         session()->flash('delete_section', 'تم حذف المنتج بنجاح');
         return back();
    }
}
