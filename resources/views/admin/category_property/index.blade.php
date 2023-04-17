@extends('layouts.admin')

@section('page-title')
    Category Property Page
@endsection

@section('page-parent-route')
    Category Property
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-action-button')
    <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Add
        Data</a>
@endsection

@section('page-content-title')
    Category Property Data
@endsection

@section('page-content-desc')
    Page to manage category property data
@endsection

@section('page-content-body')
    <div class="row">
        <div class="col-12">
            <div id="insert-box">
                <div class="card mb-4 shadow-none">
                    <div class="card-header py-2 mb-3">
                        <div class="row">
                            <div class="col-md">
                                <h6 class="my-2 font-weight-bold text-dark">
                                    {{-- <i class="ri-add-box-line btn btn-sm"></i> --}}
                                    Add Category Property Data
                                </h6>
                            </div>
                            <div class="col-md text-end my-auto">
                                <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-danger"><i
                                        class="ri-close-fill"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.category_property.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            {{-- <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">-Choose-</option>
                                @foreach (returnStatus() as $key => $value)
                                    <option value="{{ $key }}" {{ (old('status') == $key) ? "selected" : "" }}>{{ $value }}</option>
                                @endforeach
                            </select> --}}
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-end my-3">
                                <button type="reset" class="btn btn-sm btn-danger"><i
                                        class="fa fa-fw fa-undo me-1"></i>Reset</button>
                                <button type="submit" class="btn btn-sm btn-success"><i
                                        class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        @if (!$errors->any())
            $('#insert-box').hide();
        @endif
        function show_hide_insert() {
            $('#insert-box').toggle('fast', 'swing');
        }
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.category_property.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [0, "name"]
            ]
        });
    </script>
@endsection
