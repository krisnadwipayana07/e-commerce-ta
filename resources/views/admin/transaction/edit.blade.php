@extends('layouts.admin')

@section('page-title')
    Transaction  Page
@endsection

@section('page-parent-route')
    Transaction 
@endsection

@section('page-active-route')
    Edit
@endsection

@section('page-back-button')
    <a href="{{ route('admin.transaction.index') }}" class="my-2 btn btn-secondary btn-sm"><i class="ri-arrow-left-circle-line"></i> Back</a>
@endsection

@section('page-content-title')
    Edit Transaction  Data
@endsection

@section('page-desc')
    Page to edit transaction  data
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-md">
        <form method="POST" action="{{ route('admin.transaction.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                    <label>Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $data->code) }}" readonly>
                        @error('code')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Description</label>
                        {{-- <textarea name="description" id="" cols="30" rows="10" class="form-control  @error('description') is-invalid @enderror">{{ old('description') }}</textarea> --}}
                        <input name="description" type="hidden">
                        <div id="descriptionForm">
                            {!! $data->description !!}
                        </div>
                        @error('description')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                    <label>Total Payment</label>
                        <input type="number" class="form-control @error('total_payment') is-invalid @enderror" name="total_payment" value="{{ old('total_payment', $data->total_payment) }}">
                        @error('total_payment')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="text-end my-3">
                <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('page-js')
    <script>
        $(document).ready(function() {
            // setup quill for description form
            var descriptionForm = new Quill('#descriptionForm', {
                placeholder: 'write description here...',
                theme: 'snow'
            });

            var form = document.querySelector('form');
            form.onsubmit = function() {
            // Populate hidden form on submit
            var description = document.querySelector('input[name=description]');
            description.value = descriptionForm.root.innerHTML;
            };
        });
    </script>
@endsection

