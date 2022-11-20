@if ($errors->all())
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <h6 class="text-danger"><i class="fas fa-info-circle text-danger me-2"></i>
                {{ $error }}</h6>
        @endforeach
    </div>
@endif
