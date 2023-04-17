<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryProperty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryPropertyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryProperty::where('status', 'active')->orderBy('name', 'DESC')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    // ->editColumn('status', function ($data) {
                    //     return returnStatus($data->status);
                    // })
                    ->addColumn('action', function ($data) {
                        return actionBtn('Category Property', route('admin.category_property.index'), route('admin.category_property.show', $data->id), route('admin.category_property.edit', $data->id), route('admin.category_property.destroy', $data->id));
                    })
            // ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.category_property.index');
    }

    public function create()
    {
        return view('admin.category_property.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:category_properties,name',
            ],
            [],
            [
                'name' => 'Name',
            ]
        );

        DB::beginTransaction();
        try {
            $categoryProperty = CategoryProperty::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$categoryProperty->name.' Added Successfully.']);
        } catch(Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    //select * from category_property where id = $data->id
    public function show(CategoryProperty $categoryProperty)
    {
        // dd($categoryProperty);
        return view('admin.category_property.show', ['data' => $categoryProperty]);
    }

    public function edit(CategoryProperty $categoryProperty)
    {
        return view('admin.category_property.edit', ['data' => $categoryProperty]);
    }

    public function update(Request $request, CategoryProperty $categoryProperty)
    {
        $request->validate(
            [
                'name' => 'required|unique:category_properties,name,' . $categoryProperty->id,
                'status' => 'required',
            ],
            [],
            [
                'name' => 'Name',
                'status' => 'Status',
            ]
        );

        DB::beginTransaction();
        try {
            //update category_property where id = 'sdfsdfsd'
            $categoryProperty->update($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$categoryProperty->name.' Updated Successfully.']);
        } catch(Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, CategoryProperty $categoryProperty)
    {
        DB::beginTransaction();
        try {
            //delete * from catgtory_property where id = ''
            $categoryProperty->delete();
            DB::commit();
            $result = 'Data #'.$categoryProperty->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }
}
