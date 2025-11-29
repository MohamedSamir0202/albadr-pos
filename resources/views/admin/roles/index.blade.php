@extends('admin.layouts.app', [
    'pageName' => __('Roles'),
])
@section('content')
<!-- #region-->

<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0"> {{ __('Roles') }} </h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">{{ __('Dashboard') }} </a>
                        </li>
                        <li class="breadcrumb-item active">     {{ __('Roles') }} </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section id="basic-form-layouts">
        <div class="row match-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form-center"> {{ __('Roles') }} </h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="card-text">
                                <p> {{ __('Roles') }} </p>
                            </div>
                            <div class="card-text">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th> {{ __('Name') }} </th>
                                            <th> {{ __('Permissions') }} </th>
                                            <th> {{ __('Actions') }} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td> {{ $role->name }} </td>
                                                <td> {{ $role->permissions->pluck('name')->implode(', ') }} </td>
                                                <td>
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="la la-edit"></i> {{ __('Edit') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection
