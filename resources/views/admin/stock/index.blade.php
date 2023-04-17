@extends('layouts.admin')

@section('page-title')
    Admin Page
@endsection

@section('page-parent-route')
    Admin
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-title')
    Stock Data
@endsection

@section('page-content-desc')
    Page to manage Stock data
@endsection

@section('page-content-body')
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Category Property</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($stock as $stock)
                            <tr>
                                <td></td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->stock }}</td>
                                <td>
                                    <a href="{{ route('admin.stock.edit', $stock->id) }}"
                                        class="btn btn-sm btn-warning ms-2 text-white"><i class="ri-pencil-line"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
