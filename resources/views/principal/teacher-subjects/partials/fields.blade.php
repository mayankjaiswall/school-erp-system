<div class="assignment-flow">
    <div class="flow-card"><i class="bi bi-person-badge"></i><span>Teacher</span></div>
    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
    <div class="flow-card"><i class="bi bi-book"></i><span>Subject</span></div>
    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
    <div class="flow-card"><i class="bi bi-journal-bookmark"></i><span>Class</span></div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <label class="form-label">Teacher</label>
        <select name="teacher_id" class="form-select" required>
            <option value="" disabled {{ old('teacher_id', $assignment?->teacher_id) ? '' : 'selected' }}>Select Teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ old('teacher_id', $assignment?->teacher_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}{{ $teacher->qualification ? ' - '.$teacher->qualification : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
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
    <div class="col-md-4">
        <label class="form-label">Subject</label>
        <select name="subject_id" id="subjectSelect" class="form-select" required>
            <option value="" disabled selected>Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" data-class-id="{{ $subject->class_id }}" {{ old('subject_id', $assignment?->subject_id) == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }} ({{ $subject->code }})
                </option>
            @endforeach
        </select>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('schoolClassSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const selectedSubject = "{{ old('subject_id', $assignment?->subject_id) }}";

    function filterSubjects(keepSelected = false) {
        const classId = classSelect.value;

        Array.from(subjectSelect.options).forEach((option) => {
            if (!option.value) {
                return;
            }

            const isMatch = option.dataset.classId === classId;
            option.hidden = !isMatch;
            option.disabled = !isMatch;
        });

        const currentOption = subjectSelect.options[subjectSelect.selectedIndex];

        if (!keepSelected || !currentOption || currentOption.disabled) {
            subjectSelect.value = '';
        }

        if (keepSelected && selectedSubject) {
            subjectSelect.value = selectedSubject;
        }
    }

    classSelect.addEventListener('change', () => filterSubjects(false));
    filterSubjects(true);
});
</script>
