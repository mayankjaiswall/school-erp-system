@extends('layouts.admin')

@section('title', 'Role Details')

@section('page-title', 'Role Details')

@section('content')

<style>
.role-header{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    padding:30px;
    border-radius:20px;
    margin-bottom:25px;
    box-shadow:0 15px 35px rgba(37,99,235,.25);
}

.role-card{
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    border:1px solid #e2e8f0;
    box-shadow:0 8px 20px rgba(15,23,42,.05);
}

.role-profile{
    padding:40px;
    text-align:center;
    border-bottom:1px solid #e2e8f0;
}

.role-avatar{
    width:90px;
    height:90px;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
    font-weight:700;
    margin:auto;
}

.info-box{
    padding:25px;
}

.info-label{
    color:#64748b;
    font-size:13px;
    text-transform:uppercase;
    font-weight:600;
}

.info-value{
    font-size:17px;
    font-weight:600;
    color:#0f172a;
}
</style>

<div class="role-header">
    <h2 class="mb-1">
        Role Details
    </h2>
    <p class="mb-0 opacity-75">
        View complete role information.
    </p>
</div>

<div class="role-card">
    <div class="role-profile">
        <div class="role-avatar">
            {{ strtoupper(substr($role->name,0,1)) }}
        </div>
        <h3 class="mt-3 mb-1">
            {{ $role->name }}
        </h3>
        <span class="badge bg-success">
            Active Role
        </span>
    </div>

    <div class="row p-4">
        <div class="col-md-6 mb-4">
            <div class="info-label">
                Role Name
            </div>
            <div class="info-value">
                {{ $role->name }}
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="info-label">
                Role Slug
            </div>
            <div class="info-value">
                {{ $role->slug }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-label">
                Created At
            </div>
            <div class="info-value">
                {{ $role->created_at->format('d M Y h:i A') }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-label">
                Last Updated
            </div>
            <div class="info-value">
                {{ $role->updated_at->format('d M Y h:i A') }}
            </div>
        </div>
    </div>
    <div class="p-4 border-top d-flex justify-content-between">
        <a href="{{ route('roles.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
        <a href="{{ route('roles.edit',$role->id) }}"
           class="btn btn-warning text-white">
            <i class="bi bi-pencil-square"></i>
            Edit Role
        </a>
    </div>
</div>

@endsection