<div class="row g-4">
    <div class="col-md-6"><label class="form-label">Father Name</label><input type="text" name="father_name" class="form-control" value="{{ old('father_name', $parent?->father_name) }}" placeholder="Enter father name"></div>
    <div class="col-md-6"><label class="form-label">Mother Name</label><input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $parent?->mother_name) }}" placeholder="Enter mother name"></div>
    <div class="col-md-6"><label class="form-label">Mobile Number</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $parent?->phone) }}" maxlength="10" required oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"></div>
    <div class="col-md-6"><label class="form-label">Alternate Mobile</label><input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $parent?->alternate_phone) }}" maxlength="10" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"></div>
    <div class="col-md-6"><label class="form-label">Email Address</label><input type="email" name="email" class="form-control" value="{{ old('email', $parent?->email) }}" placeholder="Optional email"></div>
    <div class="col-md-6"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" value="{{ old('occupation', $parent?->occupation) }}"></div>
    <div class="col-md-6"><label class="form-label">Password {{ $parent?->exists ? '(leave blank to keep current)' : '' }}</label><input type="password" name="password" class="form-control" {{ $parent?->exists ? '' : 'required' }}></div>
    <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control" {{ $parent?->exists ? '' : 'required' }}></div>
    <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="1" {{ old('status', $parent?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option><option value="0" {{ old('status', $parent?->status) === 0 || old('status', $parent?->status) === '0' ? 'selected' : '' }}>Inactive</option></select></div>
    <div class="col-md-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address', $parent?->address) }}</textarea></div>
</div>

<div class="mt-4" id="childrenLinkSection"
    @if($parent?->exists)
        data-link-url="{{ route('principal.parents.children.link', $parent->id) }}"
        data-remove-url="{{ route('principal.parents.children.remove', $parent->id) }}"
        data-relationship-url="{{ route('principal.parents.children.relationship', $parent->id) }}"
    @endif
>
    <h5 class="mb-3">Link Children</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:70px">Link</th><th>Student Name</th><th>Class</th><th>Roll Number</th><th style="width:180px">Relationship</th></tr></thead>
            <tbody>
                @foreach($students as $student)
                    @php($linked = $linkedStudents->has($student->id))
                    <tr data-student-id="{{ $student->id }}">
                        <td><input type="checkbox" class="form-check-input child-check" name="student_ids[]" value="{{ $student->id }}" {{ $linked ? 'checked' : '' }}></td>
                        <td><strong>{{ $student->name }}</strong><br><small class="text-muted">{{ $student->admission_no }}</small></td>
                        <td>{{ $student->class?->name }}{{ $student->class?->section ? ' - '.$student->class->section : '' }}</td>
                        <td>{{ $student->roll_no ?? '-' }}</td>
                        <td>
                            <select name="relationships[{{ $student->id }}]" class="form-select relationship-select">
                                @foreach(['Father', 'Mother', 'Guardian'] as $relationship)
                                    <option value="{{ $relationship }}" {{ ($linkedStudents->get($student->id)?->pivot?->relationship ?? 'Guardian') === $relationship ? 'selected' : '' }}>{{ $relationship }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
                @if($students->isEmpty())
                    <tr><td colspan="5" class="text-center text-muted py-4">No active students found.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($parent?->exists)
        <small class="text-muted">Child links and relationship changes are saved immediately.</small>
    @endif
</div>
