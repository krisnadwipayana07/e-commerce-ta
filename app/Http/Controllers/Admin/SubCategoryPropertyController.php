<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProperty;
use App\Models\SubCategoryProperty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryPropertyController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()){
            $data = SubCategoryProperty::where('status', 'active')->with(['category_property'])->orderBy('name', 'DESC')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('category_property_id', function($data){
                        return $data->category_property->name;
                    })
                    ->editColumn('status', function($data){
                        return returnStatus($data->status);
                    })
                    ->addColumn('action', function($data){
                        return actionBtn('Category Property', route('admin.sub_category_property.index'), route('admin.sub_category_property.show', $data->id), route('admin.sub_category_property.edit', $data->id), route('admin.sub_category_property.destroy', $data->id));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $category_property = CategoryProperty::where('status','active')->orderBy('name', 'ASC')->get();
        return view('admin.sub_category_property.index', compact('category_property'));
    }

    public function create(){
        return view('admin.sub_category_property.create');
    }

    public function store(Request $request) {
        $request->validate(
            [
                'category_property_id' => 'required',
                'name' => 'required',
            ], [],
            [
                'category_property_id' => 'Category Property',
                'name' => 'Name',
            ]);
 
        DB::beginTransaction();
        try{
            $subCategoryProperty = SubCategoryProperty::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$subCategoryProperty->name.' Added Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(SubCategoryProperty $subCategoryProperty) {
        return view('admin.sub_category_property.show', ['data' => $subCategoryProperty]);
    }

    public function edit(SubCategoryProperty $subCategoryProperty) {
        $category_property = CategoryProperty::where('status','active')->orderBy('name', 'ASC')->get();
        return view('admin.sub_category_property.edit', ['data' => $subCategoryProperty, 'category_property' => $category_property]);
    }

    public function update(Request $request, SubCategoryProperty $subCategoryProperty) {
        $request->validate(
            [
                'category_property_id' => 'required',
                'name' => 'required',
                'status' => 'required',
            ], [],
            [
                'category_property_id' => 'Category Property',
                'name' => 'Name',
                'status' => 'Status',
            ]);

        DB::beginTransaction();
        try{
            $subCategoryProperty->update($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$subCategoryProperty->name.' Updated Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, SubCategoryProperty $subCategoryProperty) {
        DB::beginTransaction();
        try {
            $subCategoryProperty->delete();
            DB::commit();
            $result = 'Data #'.$subCategoryProperty->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

}
