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
        <select name="teacher_id" id="teacherSelect" class="form-select" required>
            <option value="" disabled {{ old('teacher_id', $assignment?->teacher_id) ? '' : 'selected' }}>Select Teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                        data-primary-subject-id="{{ $teacher->primary_subject_id }}"
                        data-primary-subject-name="{{ $teacher->primarySubject?->name }}"
                        {{ old('teacher_id', $assignment?->teacher_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}{{ $teacher->primarySubject ? ' - '.$teacher->primarySubject->name : '' }}
                </option>
            @endforeach
        </select>
        <div id="specializationHint" class="form-text"></div>
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
                <option value="{{ $subject->id }}"
                        data-class-id="{{ $subject->class_id }}"
                        data-subject-name="{{ $subject->name }}"
                        {{ old('subject_id', $assignment?->subject_id) == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }} ({{ $subject->code }})
                </option>
            @endforeach
        </select>
        <div id="subjectAvailabilityHint" class="form-text text-danger d-none"></div>
    </div>
    <div class="col-md-12">
        <div class="form-check">
            <input type="hidden" name="allow_specialization_override" value="0">
            @php
                $existingOverride = $assignment
                    && $assignment->teacher?->primarySubject
                    && $assignment->subject
                    && strcasecmp($assignment->teacher->primarySubject->name, $assignment->subject->name) !== 0;
            @endphp
            <input class="form-check-input" type="checkbox" value="1" id="allowSpecializationOverride" name="allow_specialization_override" {{ old('allow_specialization_override', $existingOverride) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="allowSpecializationOverride">
                Allow specialization override
            </label>
            <div class="form-text">
                Use only when the principal intentionally assigns a teacher outside their primary subject.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const teacherSelect = document.getElementById('teacherSelect');
    const classSelect = document.getElementById('schoolClassSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const overrideCheck = document.getElementById('allowSpecializationOverride');
    const specializationHint = document.getElementById('specializationHint');
    const subjectAvailabilityHint = document.getElementById('subjectAvailabilityHint');
    const selectedSubject = "{{ old('subject_id', $assignment?->subject_id) }}";

    function filterSubjects(keepSelected = false) {
        const classId = classSelect.value;
        const classLabel = classSelect.options[classSelect.selectedIndex]?.text?.trim() || 'this class';
        const teacherOption = teacherSelect.options[teacherSelect.selectedIndex];
        const primarySubjectName = teacherOption?.dataset.primarySubjectName || '';
        const allowOverride = overrideCheck.checked;
        let visibleSubjects = 0;

        Array.from(subjectSelect.options).forEach((option) => {
            if (!option.value) {
                return;
            }

            const classMatch = option.dataset.classId === classId;
            const specializationMatch = !primarySubjectName || option.dataset.subjectName.toLowerCase() === primarySubjectName.toLowerCase();
            const isMatch = classMatch && (allowOverride || specializationMatch);
            option.hidden = !isMatch;
            option.disabled = !isMatch;

            if (isMatch) {
                visibleSubjects++;
            }
        });

        specializationHint.textContent = primarySubjectName
            ? `Primary Subject: ${primarySubjectName}`
            : 'No primary subject set for this teacher.';

        const currentOption = subjectSelect.options[subjectSelect.selectedIndex];

        if (!keepSelected || !currentOption || currentOption.disabled) {
            subjectSelect.value = '';
        }

        if (keepSelected && selectedSubject) {
            subjectSelect.value = selectedSubject;
        }

        if (!subjectSelect.value && primarySubjectName && classId) {
            const preferred = Array.from(subjectSelect.options).find((option) => {
                return option.value
                    && !option.disabled
                    && option.dataset.classId === classId
                    && option.dataset.subjectName.toLowerCase() === primarySubjectName.toLowerCase();
            });

            if (preferred) {
                subjectSelect.value = preferred.value;
            }
        }

        if (classId && visibleSubjects === 0) {
            subjectAvailabilityHint.classList.remove('d-none');
            subjectAvailabilityHint.textContent = allowOverride
                ? `No subjects are created for ${classLabel}. Create the subject first from Subjects Management.`
                : `No ${primarySubjectName || 'matching'} subject is created for ${classLabel}. Create it in Subjects Management, or use specialization override to assign another subject.`;
            subjectSelect.setCustomValidity(subjectAvailabilityHint.textContent);
        } else {
            subjectAvailabilityHint.classList.add('d-none');
            subjectAvailabilityHint.textContent = '';
            subjectSelect.setCustomValidity('');
        }
    }

    teacherSelect.addEventListener('change', () => filterSubjects(false));
    classSelect.addEventListener('change', () => filterSubjects(false));
    overrideCheck.addEventListener('change', () => filterSubjects(false));
    filterSubjects(true);
});
</script>
