<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Student Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $student?->name) }}" placeholder="Enter student name" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Class</label>
        <select name="class_id" class="form-select" required>
            <option value="" disabled {{ old('class_id', $student?->class_id) ? '' : 'selected' }}>Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id', $student?->class_id) == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Admission Number</label>
        <input type="text" name="admission_no" class="form-control" value="{{ old('admission_no', $student?->admission_no) }}" placeholder="Enter admission number" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Roll Number</label>
        <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no', $student?->roll_no) }}" placeholder="Enter roll number">
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $student?->email) }}" placeholder="student@example.com">
    </div>
    <div class="col-md-6">
        <label class="form-label">Phone Number</label>
        <input type="text"
               name="phone"
               class="form-control"
               value="{{ old('phone', $student?->phone) }}"
               inputmode="numeric"
               maxlength="10"
               pattern="[0-9]{10}"
               placeholder="10 digit phone number"
               title="Enter exactly 10 digits"
               oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)">
    </div>
    <div class="col-md-6">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
            <option value="" {{ old('gender', $student?->gender) ? '' : 'selected' }}>Select Gender</option>
            <option value="male" {{ old('gender', $student?->gender) == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $student?->gender) == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $student?->gender) == 'other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" class="form-control" value="{{ old('dob', $student?->dob?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="1" {{ old('status', $student?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $student?->status) === 0 || old('status', $student?->status) === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Photo Path</label>
        <input type="text" name="photo" class="form-control" value="{{ old('photo', $student?->photo) }}" placeholder="Optional photo path">
    </div>
    <div class="col-md-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="4" placeholder="Enter student address">{{ old('address', $student?->address) }}</textarea>
    </div>
</div>
