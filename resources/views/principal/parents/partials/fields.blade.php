<style>
    .form-page-header {
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(37,99,235,.25);
        color: #fff;
        margin-bottom: 28px;
        padding: 34px 36px;
    }

    .parent-form-card {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        padding: 34px 36px;
    }

    .parent-form-card .form-label {
        display: block;
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
    }

    .parent-form-card .form-control,
    .parent-form-card .form-select {
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        min-height: 54px;
        padding: 14px 16px;
    }

    .parent-form-card textarea.form-control {
        min-height: 120px;
    }

    .parent-form-card .form-control:focus,
    .parent-form-card .form-select:focus {
        border-color: #2563eb;
        box-shadow: none;
    }

    .child-picker {
        position: relative;
    }

    .child-picker-toggle {
        align-items: center;
        background: #fff;
        border: 1px solid #dbe2ea;
        border-radius: 12px;
        color: #475569;
        display: flex;
        justify-content: space-between;
        min-height: 54px;
        padding: 14px 16px;
        text-align: left;
        width: 100%;
    }

    .child-picker-toggle:focus,
    .child-picker.open .child-picker-toggle {
        border-color: #2563eb;
        box-shadow: 0 0 0 .2rem rgba(37,99,235,.12);
        outline: none;
    }

    .child-picker-panel {
        background: #fff;
        border: 1px solid #dbe2ea;
        border-radius: 16px;
        box-shadow: 0 18px 45px rgba(15,23,42,.14);
        display: none;
        left: 0;
        max-height: 330px;
        overflow: auto;
        padding: 10px;
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        z-index: 30;
    }

    .child-picker.open .child-picker-panel {
        display: block;
    }

    .child-option {
        align-items: center;
        border: 1px solid transparent;
        border-radius: 12px;
        display: grid;
        gap: 12px;
        grid-template-columns: 28px minmax(0, 1fr) 150px;
        padding: 10px;
    }

    .child-option:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .child-option-title {
        color: #0f172a;
        font-weight: 700;
    }

    .child-option-meta {
        color: #64748b;
        font-size: 13px;
        margin-top: 3px;
    }

    .child-option .relationship-select {
        min-height: 42px;
        padding: 8px 12px;
    }

    .child-selected-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        min-height: 0;
    }

    .child-chip {
        align-items: center;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        color: #1e40af;
        display: inline-flex;
        font-size: 13px;
        font-weight: 600;
        gap: 6px;
        padding: 7px 10px;
    }

    .child-empty-state {
        color: #64748b;
        padding: 18px 10px;
        text-align: center;
    }

    .action-footer {
        border-top: 1px solid #e2e8f0;
        margin-top: 36px;
        padding-top: 24px;
    }

    @media (max-width: 768px) {
        .form-page-header,
        .parent-form-card {
            padding: 26px 22px;
        }

        .child-option {
            grid-template-columns: 28px minmax(0, 1fr);
        }

        .child-option .relationship-select {
            grid-column: 2;
        }
    }
</style>

<div class="row g-5">
    <div class="col-md-6">
        <label class="form-label">Father Name</label>
        <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $parent?->father_name) }}" placeholder="Enter father name">
    </div>
    <div class="col-md-6">
        <label class="form-label">Mother Name</label>
        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $parent?->mother_name) }}" placeholder="Enter mother name">
    </div>
    <div class="col-md-6">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $parent?->phone) }}" maxlength="10" required oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
    </div>
    <div class="col-md-6" id="childrenLinkSection"
    @if($parent?->exists)
        data-link-url="{{ route('principal.parents.children.link', $parent->id) }}"
        data-remove-url="{{ route('principal.parents.children.remove', $parent->id) }}"
        data-relationship-url="{{ route('principal.parents.children.relationship', $parent->id) }}"
    @endif
    >
        <label class="form-label">Link Children</label>
        <div class="child-picker" data-child-picker>
            <button type="button" class="child-picker-toggle" aria-expanded="false">
                <span data-child-picker-text>Select children</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="child-picker-panel">
                @foreach($students as $student)
                    @php($linked = $linkedStudents->has($student->id))
                    <div class="child-option" data-child-option data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                        <input type="checkbox" class="form-check-input child-check" name="student_ids[]" value="{{ $student->id }}" {{ $linked ? 'checked' : '' }}>
                        <div>
                            <div class="child-option-title">{{ $student->name }}</div>
                            <div class="child-option-meta">
                                {{ $student->admission_no }}
                                &middot;
                                {{ $student->class?->name }}{{ $student->class?->section ? ' - '.$student->class->section : '' }}
                                &middot;
                                Roll {{ $student->roll_no ?? '-' }}
                            </div>
                        </div>
                        <select name="relationships[{{ $student->id }}]" class="form-select relationship-select">
                            @foreach(['Father', 'Mother', 'Guardian'] as $relationship)
                                <option value="{{ $relationship }}" {{ ($linkedStudents->get($student->id)?->pivot?->relationship ?? 'Guardian') === $relationship ? 'selected' : '' }}>{{ $relationship }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
                @if($students->isEmpty())
                    <div class="child-empty-state">No active students found.</div>
                @endif
            </div>
        </div>
        <div class="child-selected-list" data-child-selected-list></div>
        @if($parent?->exists)
            <small class="text-muted d-block mt-2">Child links and relationship changes are saved immediately.</small>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Alternate Mobile</label>
        <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $parent?->alternate_phone) }}" maxlength="10" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $parent?->email) }}" placeholder="Optional email">
    </div>
    <div class="col-md-6">
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $parent?->occupation) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Password {{ $parent?->exists ? '(leave blank to keep current)' : '' }}</label>
        <input type="password" name="password" class="form-control" {{ $parent?->exists ? '' : 'required' }}>
    </div>
    <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" {{ $parent?->exists ? '' : 'required' }}>
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="1" {{ old('status', $parent?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $parent?->status) === 0 || old('status', $parent?->status) === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3">{{ old('address', $parent?->address) }}</textarea>
    </div>
</div>
