@extends('layouts.admin')

@section('title', 'Schools')

@section('page-title', 'School Management')

@section('content')

<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Schools List</h4>
            <small class="text-muted">
                Total Schools : {{ $schools->count() }}
            </small>
        </div>

        <a href="{{ route('schools.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add School
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>
                    <th>#</th>
                    <th>School Name</th>
                    <th>Code</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="180">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($schools as $school)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $school->name }}
                        </td>

                        <td>
                            {{ $school->code }}
                        </td>

                        <td>
                            {{ $school->email }}
                        </td>

                        <td>
                            {{ $school->phone }}
                        </td>

                        <td>

                            @if($school->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $school->created_at ? $school->created_at->format('d M Y') : '-' }}
                        </td>

                        <td>

                            <a href="#"
                               class="btn btn-sm btn-info text-white"
                               title="View">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('schools.edit', $school->id) }}"
                               class="btn btn-sm btn-warning"
                               title="Edit">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <form action="{{ route('schools.destroy', $school->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this school?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-building fs-3 d-block mb-2"></i>
                            No Schools Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection