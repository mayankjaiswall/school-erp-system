@extends('layouts.admin')

@section('title', 'Schools')

@section('page-title', 'School Management')

@section('content')

<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">Schools List</h4>

        <a href="{{ route('schools.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add School
        </a>

    </div>

    <table class="table table-hover">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($schools as $school)

                <tr>
                    <td>{{ $school->id }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->code }}</td>
                    <td>{{ $school->email }}</td>
                    <td>
                        @if($school->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No Schools Found
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection