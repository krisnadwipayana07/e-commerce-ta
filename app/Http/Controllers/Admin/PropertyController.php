<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProperty;
use App\Models\Property;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()){
            $data = Property::where('status', 'active')->with(['sub_category_property'])->orderBy('name', 'DESC')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('sub_category_property', function($data){
                        return $data->sub_category_property->name;
                    })
                    ->editColumn('price', function($data){
                        return format_rupiah($data->price);
                    })
                    ->editColumn('status', function($data){
                        return returnStatus($data->status);
                    })
                    ->addColumn('action', function($data){
                        return actionBtnProperty('Property Recipe', route('admin.property.index'), route('admin.property.show', $data->id), route('admin.property.edit', $data->id), route('admin.property.destroy', $data->id), route('admin.property.add_stock', $data->id));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $category_property = CategoryProperty::where('status','active')
                                            ->with([
                                                'sub_category_property' => function($q){
                                                    return $q->where('status','active')
                                                            ->get();
                                                }
                                            ])
                                            ->orderBy('name', 'ASC')->get();
        return view('admin.property.index', compact('category_property'));
    }

    public function create(){
        return view('admin.property.create');
    }

    public function store(Request $request) {
        $request->validate(
            [
                'name' => 'required|unique:properties,name',
                'description' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'sub_category_property_id' => 'required',
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'name' => 'Name',
                'description' => 'Description',
                'price' => 'Price',
                'stock' => 'Stock',
                'sub_category_property_id' => 'Category Property',
                'myimg' => 'Image',
            ]);
 
        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/admin/property/');
                $request['img'] = $img_name;
            }
            $property = Property::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$property->name.' Added Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(Property $property) {
        return view('admin.property.show', ['data' => $property]);
    }

    public function edit(Property $property) {
        $category_property = CategoryProperty::where('status','active')
                                            ->with([
                                                'sub_category_property' => function($q){
                                                    return $q->where('status','active')
                                                            ->get();
                                                }
                                            ])
                                            ->orderBy('name', 'ASC')->get();
        return view('admin.property.edit', ['data' => $property, 'category_property' => $category_property]);
    }

    public function update(Request $request, Property $property) {
        $request->validate(
            [
                'name' => 'required|unique:properties,name,'. $property->id,
                'description' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'sub_category_property_id' => 'required',
                'status' => 'required',
                'myimg' =>'nullable|mimes:jpeg,png|max:2048',
            ], [],
            [
                'name' => 'Name',
                'description' => 'Description',
                'price' => 'Price',
                'stock' => 'Stock',
                'sub_category_property_id' => 'Category Property',
                'status' => 'Status',
                'myimg' => 'Image',
            ]);

        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $request['img'] = updateImg('upload/admin/property/', $property->img);
            }
            
            $property->update($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$property->name.' Updated Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, Property $property) {
        DB::beginTransaction();
        try {
            
            // deleteImg('upload/admin/property/', $property->img);

            $property->delete();
            DB::commit();
            $result = 'Data #'.$property->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function add_stock($id) {
        $property =  Property::where('id', $id)->first();
        $category_property = CategoryProperty::where('status','active')
                                            ->with([
                                                'sub_category_property' => function($q){
                                                    return $q->where('status','active')
                                                            ->get();
                                                }
                                            ])
                                            ->orderBy('name', 'ASC')->get();
        return view('admin.property.add_stock', ['data' => $property, 'category_property' => $category_property]);
    }

    public function store_stock(Request $request, $id) {
        DB::beginTransaction();
        try {
            $request->validate([
                'stock' => 'required'
            ], [],
            [
                'stock' => 'Stock'
            ]);

            $property = Property::where('id', $id)->first();
            $new_stock = $property->stock + $request->stock;
            $property->update([
                'stock' => $new_stock
            ]);

            DB::commit();
            return redirect()->route('admin.property.index')->with("result", ["success", "Add stock success " . $property->name]);
        } catch(Exception $ex) {
            DB::rollBack();
            Log::debug($ex);
            return response()->back()->with("result", ["error", "Failed"]);
        }
    }
}
