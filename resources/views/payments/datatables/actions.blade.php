<div class="dropdown custom-dropdown">
    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
        <a class="dropdown-item" href="{{ route('financial.payments.refund', $model->uuid) }}">Estornar</a>
    </div>
</div>
