<?php

namespace App\Http\Controllers;

use App\Models\CategoryProperty;
use App\Models\Property;
use App\Models\SubCategoryProperty;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    protected $category_product;
    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }
    public function index(Request $request)
    {
        $isKeyword = $request->keyword != null && $request->keyword != "";
        $keyword = $request->keyword;
        // $page = $request['page'];
        $property = Property::when($isKeyword, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        })->simplePaginate(10);
        // return view('landing.index');
        return view('landing.home', [
            'property' => $property,
            'header_category' => $this->category_product
        ]);
    }

    public function product(Request $request, $id)
    {
        $category = CategoryProperty::where('status', 'active')->get();
        $sub_category = $request->sub_category ?? "";
        $isKeyword = $request->keyword != null && $request->keyword != "";
        $keyword = $request->keyword;
        $property = Property::join('sub_category_properties as scp', 'properties.sub_category_property_id', 'scp.id')
                    ->join('category_properties as cp', 'scp.category_property_id', 'cp.id')
                    ->where('cp.id', $id)
                    ->when($isKeyword, function ($query) use ($keyword) {
                        $query->where('properties.name', 'LIKE', "%{$keyword}%");
                    })
                    ->when($sub_category <> "", function ($query) use ($sub_category) {
                        $query->where('scp.id', $sub_category);
                    })
                    ->select(['properties.*','scp.name as sub_name','scp.category_property_id as sub_id'])
                    ->get();
        $count = $property->count();
        $thisCategory = CategoryProperty::find($id);
        $subCategory = SubCategoryProperty::where("category_property_id", $id)->get();
        return view('landing.product', [
            'count' => $count,
            'property' => $property,
            'category' => $category,
            'header_category' => $this->category_product,
            'thisCategory' => $thisCategory,
            'subCategory' => $subCategory,
        ]);
    }

    public function detail(Property $property)
    {
        return view('landing.detail', [
            'property' => $property,
            'header_category' => $this->category_product
        ]);
    }
}
