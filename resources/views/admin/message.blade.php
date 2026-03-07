@if (session('mensaje'))
    <div class="alert alert-success alert-dismissible fade show alert-admin" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('mensaje') }}
    </div>
@endif
