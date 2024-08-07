@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Digunakan untuk menambahkan Master Data Roles</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('role.update',$role->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" name="name" value="{{ $role->name }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permission</label>
                                    <br>
                                    @php
                                    $groupedPermissions = [];

                                    foreach ($permissions as $permission) {
                                    $parts = explode('-', $permission->name, 2);
                                    if (count($parts) === 2) {
                                    $groupedPermissions[$parts[0]][] = $permission;
                                    } else {
                                    $groupedPermissions['others'][] = $permission; // Untuk permissions tanpa tanda "-"
                                    }
                                    }

                                    @endphp

                                    <div class="permission-sections">
                                        @foreach($groupedPermissions as $group => $permissions)
                                        <div class="card permission-card">
                                            <div class="card-body">
                                                <div class="permission-group">
                                                    <h5 class="card-title">{{ ucfirst($group) }}</h5>
                                                    @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permission[]" value="{{ $permission->id }}" {{
                                                            array_key_exists($permission->id, $role_permission) ?
                                                        'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            {{ ucfirst(explode('-', $permission->name, 2)[1] ??
                                                            $permission->name) }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <a class="btn btn-danger" href="{{ route('role.index') }}">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection

@push('css')
<style>
    .permission-sections {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .permission-card {
        width: auto;
        /* Adjust the width as needed */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 20px;
    }
</style>
@endpush
