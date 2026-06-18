@if($standalone ?? false)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
</head>
<body>
@endif

<style>
    .report-card-wrap{font-family:Arial,Helvetica,sans-serif;color:#111827}
    .report-card{background:#fff;border:1px solid #d1d5db;margin:0 auto 24px;max-width:820px;padding:28px;page-break-after:always}
    .report-card:last-child{page-break-after:auto}
    .report-top-table{border-bottom:3px solid #1d4ed8;border-collapse:collapse;margin-bottom:18px;padding-bottom:16px;width:100%}
    .report-top-table td{padding-bottom:16px;vertical-align:middle}
    .logo-cell{width:86px}
    .badge-cell{width:120px}
    .school-logo{background:#1d4ed8;border-radius:10px;color:#fff;font-size:30px;font-weight:700;height:72px;line-height:72px;overflow:hidden;text-align:center;width:72px}
    .school-logo img{height:72px;object-fit:contain;width:72px}
    .school-title{text-align:center}
    .school-title h1{font-size:28px;font-weight:800;margin:0;text-transform:uppercase}
    .school-title p{color:#4b5563;font-size:13px;margin:5px 0 0}
    .report-badge{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;color:#1d4ed8;font-size:12px;font-weight:700;padding:8px 12px;text-align:center;text-transform:uppercase}
    .meta-strip{background:#f8fafc;border:1px solid #e5e7eb;border-collapse:collapse;border-radius:8px;margin:18px 0;width:100%}
    .meta-strip td{font-size:13px;padding:12px 14px}
    .student-table{border:1px solid #d1d5db;border-collapse:collapse;margin-bottom:18px;width:100%}
    .student-table td{border:1px solid #e5e7eb;padding:10px 12px;width:50%}
    .label{color:#6b7280;display:block;font-size:11px;font-weight:700;text-transform:uppercase}
    .value{font-size:14px;font-weight:700;margin-top:3px}
    .report-table{border-collapse:collapse;margin-bottom:18px;width:100%}
    .report-table th{background:#1f2937;color:#fff;font-size:12px;padding:10px;text-align:left;text-transform:uppercase}
    .report-table td{border:1px solid #d1d5db;font-size:13px;padding:10px}
    .report-table tbody tr:nth-child(even) td{background:#f9fafb}
    .summary-table{border-collapse:separate;border-spacing:8px;margin-bottom:26px;width:100%}
    .summary-table td{width:33.33%}
    .summary-item{background:#f8fafc;border:1px solid #dbeafe;border-radius:8px;padding:11px 12px}
    .summary-item .label{color:#475569}
    .summary-item .value{font-size:18px}
    .result-pass{color:#15803d}
    .result-fail{color:#b91c1c}
    .signature-table{border-collapse:collapse;margin-top:44px;width:100%}
    .signature-table td{width:50%}
    .signature-box{border-top:1px solid #111827;font-size:13px;font-weight:700;padding-top:8px;text-align:center;width:220px}
    .signature-right{margin-left:auto}
    .text-end{text-align:right}
    .text-center{text-align:center}
    .muted{color:#6b7280}
    @media print{
        body{background:#fff;margin:0}
        .report-card{border:0;margin:0;max-width:none;padding:18mm}
    }
</style>

<div class="report-card-wrap">
    @foreach($reportCards as $reportCard)
        @php
            $school = $reportCard['school'];
            $exam = $reportCard['exam'];
            $student = $reportCard['student'];
            $class = $reportCard['class'];
            $summary = $reportCard['summary'];
            $attendance = $reportCard['attendance'];
            $className = $class->name . ($class->section ? ' - '.$class->section : '');
            $resultClass = $summary['result'] === 'PASS' ? 'result-pass' : 'result-fail';
        @endphp

        <section class="report-card">
            <table class="report-top-table">
                <tr>
                    <td class="logo-cell">
                        <div class="school-logo">
                            @if($schoolLogo)
                                <img src="{{ $schoolLogo }}" alt="School Logo">
                            @else
                                {{ strtoupper(substr($school?->name ?? 'S', 0, 1)) }}
                            @endif
                        </div>
                    </td>
                    <td class="school-title">
                        <h1>{{ $school?->name ?? config('app.name') }}</h1>
                        <p>{{ $school?->address }}</p>
                        <p>{{ $school?->email }}{{ $school?->phone ? ' | '.$school->phone : '' }}</p>
                    </td>
                    <td class="badge-cell"><div class="report-badge">Report Card</div></td>
                </tr>
            </table>

            <table class="meta-strip">
                <tr>
                    <td><strong>Academic Session:</strong> {{ $exam->academic_year }}</td>
                    <td><strong>Exam:</strong> {{ $exam->name }}</td>
                    <td><strong>Date:</strong> {{ optional($exam->exam_date)->format('d M Y') }}</td>
                </tr>
            </table>

            <table class="student-table">
                <tr>
                    <td>
                    <span class="label">Student Name</span>
                    <span class="value">{{ $student->name }}</span>
                    </td>
                    <td>
                    <span class="label">Admission Number</span>
                    <span class="value">{{ $student->admission_no }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <span class="label">Roll Number</span>
                    <span class="value">{{ $student->roll_no ?? '-' }}</span>
                    </td>
                    <td>
                    <span class="label">Class / Section</span>
                    <span class="value">{{ $className }}</span>
                    </td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th class="text-end">Max Marks</th>
                        <th class="text-end">Obtained Marks</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportCard['subjects'] as $subject)
                        <tr>
                            <td>{{ $subject['name'] }}</td>
                            <td class="text-end">{{ number_format($subject['max_marks'], 2) }}</td>
                            <td class="text-end">{{ number_format($subject['obtained_marks'], 2) }}</td>
                            <td class="text-center">{{ $subject['grade'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="summary-table">
                <tr>
                    <td><div class="summary-item">
                        <span class="label">Total Marks</span>
                        <span class="value">{{ number_format($summary['total_marks'], 2) }}</span>
                    </div></td>
                    <td><div class="summary-item">
                        <span class="label">Obtained Marks</span>
                        <span class="value">{{ number_format($summary['obtained_marks'], 2) }}</span>
                    </div></td>
                    <td><div class="summary-item">
                        <span class="label">Percentage</span>
                        <span class="value">{{ number_format($summary['percentage'], 2) }}%</span>
                    </div></td>
                </tr>
                <tr>
                    <td><div class="summary-item">
                        <span class="label">Attendance</span>
                        <span class="value">{{ number_format($attendance['percentage'], 2) }}%</span>
                        <div class="muted">{{ $attendance['present_days'] }} / {{ $attendance['total_days'] }} days</div>
                    </div></td>
                    <td><div class="summary-item">
                        <span class="label">Overall Grade</span>
                        <span class="value">{{ $summary['overall_grade'] }}</span>
                    </div></td>
                    <td><div class="summary-item">
                        <span class="label">Result</span>
                        <span class="value {{ $resultClass }}">{{ $summary['result'] }}</span>
                    </div></td>
                </tr>
            </table>

            <table class="signature-table">
                <tr>
                    <td><div class="signature-box">Class Teacher Signature</div></td>
                    <td><div class="signature-box signature-right">Principal Signature</div></td>
                </tr>
            </table>
        </section>
    @endforeach
</div>

@if($printMode ?? false)
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
@endif

@if($standalone ?? false)
</body>
</html>
@endif
