@forelse($plans as $plan)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <strong class="plan-name">{{ $plan->plan_name }}</strong>
            @if($plan->description)
                <div class="plan-description">{{ \Illuminate\Support\Str::limit($plan->description, 80) }}</div>
            @endif
        </td>
        <td>{{ $plan->duration }} {{ $plan->duration_type }}</td>
        <td>₹{{ number_format((float) $plan->price, 2) }}</td>
        <td>
            @if($plan->status)
                <span class="status-pill active">Active</span>
            @else
                <span class="status-pill inactive">Inactive</span>
            @endif
        </td>
        <td>{{ $plan->created_at->format('d M Y') }}</td>
        <td>
            <button type="button" class="btn-action btn-view view-plan" data-id="{{ $plan->id }}" title="View">
                <i class="bi bi-eye"></i>
            </button>
            <button type="button" class="btn-action btn-edit edit-plan" data-id="{{ $plan->id }}" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button type="button" class="btn-action btn-delete delete-plan" data-id="{{ $plan->id }}" data-name="{{ $plan->plan_name }}" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">
            <div class="empty-state">
                <i class="bi bi-credit-card"></i>
                <h5>No Subscription Plans Found</h5>
                <p>Start by creating your first plan.</p>
            </div>
        </td>
    </tr>
@endforelse
