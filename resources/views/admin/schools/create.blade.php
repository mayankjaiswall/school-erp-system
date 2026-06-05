@extends('layouts.admin')

@section('title', 'Create School')

@section('page-title', 'Add New School')

@section('content')

<div class="content-card">

    <h4 class="mb-4">Create School</h4>

    <form>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>School Name</label>
                <input type="text"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>School Code</label>
                <input type="text"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text"
                       class="form-control">
            </div>

            <div class="col-12 mb-3">
                <label>Address</label>
                <textarea class="form-control"
                          rows="3"></textarea>
            </div>

            <div class="col-md-3 mb-3">
                <label>Status</label>

                <select class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

        </div>

        <button class="btn btn-primary">
            Save School
        </button>

    </form>

</div>

@endsection