<div class="assignment-flow">
    <div class="flow-card"><i class="bi bi-person-badge"></i><span>Teacher</span></div>
    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
    <div class="flow-card"><i class="bi bi-book"></i><span>Primary Subject</span></div>
    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
    <div class="flow-card"><i class="bi bi-journal-bookmark"></i><span>Class</span></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Teacher</label>
        <select name="teacher_id" id="teacherSelect" class="form-select" required>
            <option value="" disabled {{ old('teacher_id', $assignment?->teacher_id) ? '' : 'selected' }}>Select Teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                        data-primary-subject-name="{{ $teacher->primarySubject?->name }}"
                        {{ old('teacher_id', $assignment?->teacher_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}{{ $teacher->primarySubject ? ' - '.$teacher->primarySubject->name : '' }}
                </option>
            @endforeach
        </select>
        <div id="specializationHint" class="form-text"></div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Class</label>
        <select name="school_class_id" id="schoolClassSelect" class="form-select" required>
            <option value="" disabled {{ old('school_class_id', $assignment?->school_class_id) ? '' : 'selected' }}>Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('school_class_id', $assignment?->school_class_id) == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const teacherSelect = document.getElementById('teacherSelect');
    const specializationHint = document.getElementById('specializationHint');

    function updateSpecializationHint() {
        const teacherOption = teacherSelect.options[teacherSelect.selectedIndex];
        const primarySubjectName = teacherOption?.dataset.primarySubjectName || '';

        specializationHint.classList.toggle('text-danger', !primarySubjectName && Boolean(teacherSelect.value));
        specializationHint.textContent = primarySubjectName
            ? `Primary Subject: ${primarySubjectName}`
            : (teacherSelect.value ? 'This teacher does not have a Primary Subject assigned.' : '');
    }

    teacherSelect.addEventListener('change', updateSpecializationHint);
    updateSpecializationHint();
});
</script>
