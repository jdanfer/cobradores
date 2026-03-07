@if (session('mensajealerta'))
    <div class="alert bg-danger alert-danger alert-dismissible fade show alert-admin">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('mensajealerta') }}
    </div>
@endif
