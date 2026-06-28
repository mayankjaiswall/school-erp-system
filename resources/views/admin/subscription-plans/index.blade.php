@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('page-title', 'Subscription Plans')

@section('content')

<style>
    .plans-list-header{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color: #fff;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 35px rgba(37,99,235,.25);
    }

    .plans-list-header h2{
        margin: 0;
        font-weight: 700;
    }

    .stats-card{
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        text-align: center;
    }

    .stats-card h3{
        margin: 0;
        color: #2563eb;
        font-weight: 700;
    }

    .stats-card span{
        color: #64748b;
        font-size: 14px;
    }

    .table-card{
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        border: 1px solid #e2e8f0;
    }

    .status-pill{
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-pill.active{
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.inactive{
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action{
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin-right: 5px;
        transition: .3s;
    }

    .btn-view{
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-view:hover{
        background: #2563eb;
        color: #fff;
    }

    .btn-edit{
        background: #fef3c7;
        color: #d97706;
    }

    .btn-edit:hover{
        background: #d97706;
        color: #fff;
    }

    .btn-delete{
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover{
        background: #dc2626;
        color: #fff;
    }

    .table thead th{
        border: none;
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
    }

    .table tbody tr:hover{
        background: #f8fafc;
    }

    .plans-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .plans-table thead th {
        padding: 15px 14px;
    }

    .plans-table tbody td {
        border-bottom: 1px solid #eef2f7;
        line-height: 1.65;
        padding: 20px 14px;
        vertical-align: middle;
    }

    .plans-table tbody tr:last-child td {
        border-bottom: none;
    }

    .plan-name {
        color: #0f172a;
        display: block;
        font-size: 15px;
        line-height: 1.45;
        margin-bottom: 3px;
    }

    .plan-description {
        color: #64748b;
        font-size: 13px;
        line-height: 1.55;
        max-width: 360px;
    }

    .empty-state{
        padding: 50px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i{
        font-size: 50px;
        margin-bottom: 15px;
        display: block;
        color: #cbd5e1;
    }

    .modal-content{
        border: none;
        border-radius: 18px;
        box-shadow: 0 18px 45px rgba(15,23,42,.16);
    }

    .modal-header{
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }

    .modal-body{
        padding: 24px;
    }

    .modal-footer{
        border-top: 1px solid #e2e8f0;
        padding: 18px 24px;
    }

    .form-label{
        color: #334155;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select{
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        min-height: 48px;
    }

    .form-control:focus,
    .form-select:focus{
        border-color: #2563eb;
        box-shadow: none;
    }

    .invalid-feedback{
        display: block;
    }
</style>

<div class="plans-list-header">
    <div>
        <h2>Subscription Plans</h2>
        <p class="mb-0 opacity-75">Manage all subscription plans from one dashboard.</p>
    </div>
    <button type="button" class="btn btn-light" id="createPlanBtn">
        <i class="bi bi-plus-circle"></i>
        Create Plan
    </button>
</div>

<div class="row mb-4 g-4">
    <div class="col-md-4">
        <div class="stats-card">
            <h3 id="totalPlans">{{ $totalPlans }}</h3>
            <span>Total Plans</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h3 id="activePlans">{{ $activePlans }}</h3>
            <span>Active Plans</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h3 id="inactivePlans">{{ $inactivePlans }}</h3>
            <span>Inactive Plans</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Plans Directory {{ $plans->count() }}</h5>
        <form action="{{ route('subscription-plans.index') }}" method="GET" class="d-flex gap-2" role="search" id="plansSearchForm">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search plans..."
                   style="min-width:260px">
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle plans-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plan Name</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody id="plansTableBody">
                @include('admin.subscription-plans.partials.table', ['plans' => $plans])
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" id="planForm">
            @csrf
            <input type="hidden" id="planId">
            <div class="modal-header">
                <h5 class="modal-title" id="planModalTitle">Create Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Plan Name</label>
                        <input type="text" name="plan_name" class="form-control">
                        <div class="invalid-feedback" data-error="plan_name"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duration</label>
                        <input type="number" name="duration" class="form-control" min="1">
                        <div class="invalid-feedback" data-error="duration"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duration Type</label>
                        <select name="duration_type" class="form-select">
                            <option value="Days">Days</option>
                            <option value="Months">Months</option>
                            <option value="Years">Years</option>
                        </select>
                        <div class="invalid-feedback" data-error="duration_type"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" min="0" step="0.01">
                        <div class="invalid-feedback" data-error="price"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" data-error="status"></div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                        <div class="invalid-feedback" data-error="description"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="savePlanBtn">
                    <i class="bi bi-save"></i>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="viewPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Plan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3" id="planDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function () {
    const planModal = new bootstrap.Modal(document.getElementById('planModal'));
    const viewPlanModal = new bootstrap.Modal(document.getElementById('viewPlanModal'));
    const form = $('#planForm');
    const toast = Swal.mixin({toast:true, position:'top-end', timer:1600, showConfirmButton:false});

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': form.find('input[name="_token"]').val()}
    });

    function clearErrors() {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('[data-error]').text('');
    }

    function resetForm() {
        form[0].reset();
        $('#planId').val('');
        clearErrors();
        form.find('[name="duration_type"]').val('Months');
        form.find('[name="status"]').val('1');
    }

    function fillForm(plan) {
        $('#planId').val(plan.id || '');
        form.find('[name="plan_name"]').val(plan.plan_name || '');
        form.find('[name="description"]').val(plan.description || '');
        form.find('[name="duration"]').val(plan.duration || '');
        form.find('[name="duration_type"]').val(plan.duration_type || 'Months');
        form.find('[name="price"]').val(plan.price || '');
        form.find('[name="status"]').val(String(plan.status ?? '1'));
    }

    function renderErrors(errors) {
        clearErrors();

        Object.keys(errors || {}).forEach(function (field) {
            const input = form.find(`[name="${field}"]`);
            input.addClass('is-invalid');
            form.find(`[data-error="${field}"]`).text(errors[field][0]);
        });
    }

    function loadPlans() {
        $.ajax({
            url: "{{ route('subscription-plans.index') }}",
            method: 'GET',
            data: {search: $('#plansSearchForm input[name="search"]').val()},
            dataType: 'json',
            success: function (response) {
                $('#plansTableBody').html(response.html);
                $('#totalPlans').text(response.totalPlans);
                $('#activePlans').text(response.activePlans);
                $('#inactivePlans').text(response.inactivePlans);
                $('#recordsFound').text(response.recordsFound);
            }
        });
    }

    $('#createPlanBtn').on('click', function () {
        resetForm();
        $('#planModalTitle').text('Create Plan');
        planModal.show();
    });

    $(document).on('click', '.edit-plan', function () {
        resetForm();
        $('#planModalTitle').text('Edit Plan');

        $.get("{{ url('/admin/subscription-plans/edit') }}/" + $(this).data('id'), function (response) {
            fillForm(response.plan);
            planModal.show();
        });
    });

    $(document).on('click', '.view-plan', function () {
        $.get("{{ url('/admin/subscription-plans/view') }}/" + $(this).data('id'), function (response) {
            const plan = response.plan;
            $('#planDetails').html(`
                <div class="col-12"><small class="text-muted">Plan Name</small><h5>${plan.plan_name}</h5></div>
                <div class="col-12"><small class="text-muted">Description</small><div>${plan.description}</div></div>
                <div class="col-6"><small class="text-muted">Duration</small><div class="fw-semibold">${plan.duration}</div></div>
                <div class="col-6"><small class="text-muted">Price</small><div class="fw-semibold">₹${plan.price}</div></div>
                <div class="col-6"><small class="text-muted">Status</small><div class="fw-semibold">${plan.status}</div></div>
                <div class="col-6"><small class="text-muted">Created Date</small><div class="fw-semibold">${plan.created_date}</div></div>
                <div class="col-12"><small class="text-muted">Updated Date</small><div class="fw-semibold">${plan.updated_date}</div></div>
            `);
            viewPlanModal.show();
        });
    });

    form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        const id = $('#planId').val();
        const url = id
            ? "{{ url('/admin/subscription-plans/update') }}/" + id
            : "{{ route('subscription-plans.store') }}";

        $('#savePlanBtn').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                planModal.hide();
                toast.fire({icon:'success', title: response.message});
                loadPlans();
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    renderErrors(xhr.responseJSON.errors);
                    return;
                }

                toast.fire({icon:'error', title: xhr.responseJSON?.message || 'Unable to save plan.'});
            },
            complete: function () {
                $('#savePlanBtn').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.delete-plan', function () {
        const id = $(this).data('id');
        const planName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This plan will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {popup: 'rounded-4'}
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: "{{ url('/admin/subscription-plans/delete') }}/" + id,
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    toast.fire({icon:'success', title: response.message || `${planName} deleted`});
                    loadPlans();
                },
                error: function (xhr) {
                    toast.fire({icon:'error', title: xhr.responseJSON?.message || 'Unable to delete plan.'});
                }
            });
        });
    });

    let searchTimer = null;
    $('#plansSearchForm input[name="search"]').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadPlans, 350);
    });
});
</script>
@endsection
