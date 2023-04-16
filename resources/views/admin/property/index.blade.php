@extends('layouts.admin')

@section('page-title')
    Property Page
@endsection

@section('page-parent-route')
    Property
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-action-button')
    <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Add Data</a>
@endsection

@section('page-content-title')
    Property Data
@endsection

@section('page-content-desc')
    Page to manage property data
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
                                    Add Property Data
                                </h6>
                            </div>
                            <div class="col-md text-end my-auto">
                                <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-danger"><i class="ri-close-fill"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.property.store') }}" enctype="multipart/form-data">
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
                                    <label>Category</label>
                                        <select name="sub_category_property_id" class="select-picker form-control @error('sub_category_property_id') is-invalid @enderror"  style="width:100%;">
                                            <option value="">-Choose-</option>
                                            @foreach ($category_property as $category)
                                                <optgroup label="{{ $category->name }}">
                                                    {{-- <option disabled>{{ $category->name }}</option> --}}
                                                    @foreach ($category->sub_category_property as $sub_category)
                                                        <option value="{{ $sub_category->id }}" {{ (old('sub_category_property_id') == $sub_category->id) ? "selected" : "" }}>
                                                            {{ $sub_category->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('sub_category_property_id')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                    <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                        @error('name')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="" cols="30" rows="10" class="form-control  @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                        @error('description')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                    <label>Price</label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}">
                                        @error('price')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                    <label>Stock</label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}">
                                        @error('stock')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="form-group">
                                    <label>Image</label>
                                        <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
                                        @error('myimg')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12 my-2">
                                    <img id="preview-myimg" src="https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png" style="height: 300px;">
                                </div>
                            </div>
                            <div class="text-end my-3">
                                <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
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
                        <th>Price</th>
                        {{-- <th>Stock</th> --}}
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
        $(document).ready(function() {
            // for display myimg
            $('#myimg').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => {
                $('#preview-myimg').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0   ]);
            });
        });

        @if ( ! $errors->any())
        $('#insert-box').hide();
        @endif
        function show_hide_insert() {
            $('#insert-box').toggle('fast', 'swing');
        }
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.property.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                // {
                //     data: 'stock',
                //     name: 'stock'
                // },
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
            order: [[ 0, "name" ]]
        });

    </script>
@endsection
