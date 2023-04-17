
<form method="POST" action="{{ route('import.admin') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-md">
            <div class="form-group">
            <label>File</label>
                <input type="file" class="form-control @error('file_import') is-invalid @enderror" name="file_import" value="{{ old('file_import') }}" id="file_import">
                @error('file_import')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="float-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo mr-1"></i>Reset</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane mr-1"></i>Submit</button>
    </div>
</form>