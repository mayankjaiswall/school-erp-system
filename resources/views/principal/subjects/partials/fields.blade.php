<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Subject Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $subject?->name) }}" placeholder="Enter subject name" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Class</label>
        <select name="class_id" class="form-select" required>
            <option value="" disabled {{ old('class_id', $subject?->class_id) ? '' : 'selected' }}>Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id', $subject?->class_id) == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Subject Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $subject?->code) }}" placeholder="Example: ENG-1" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="1" {{ old('status', $subject?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $subject?->status) === 0 || old('status', $subject?->status) === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Enter subject description">{{ old('description', $subject?->description) }}</textarea>
    </div>
</div>
