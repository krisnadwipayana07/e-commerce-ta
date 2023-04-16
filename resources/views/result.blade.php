@if(session('result'))
    @if(count(session('result')) == 2)
        @if(session('result')[0] == "success")
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line"></i>
                <b> Gotcha!</b> {{ session('result')[1] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('result')[0] == "error")
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line"></i>
                <b> Whoops!</b> {{ session('result')[1] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <div class="alert alert-{{ session('result')[0] }} alert-dismissible fade show rounded-4" role="alert">
                <i class="ri-information-line"></i>
                 {{ session('result')[1] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endif
@endif
