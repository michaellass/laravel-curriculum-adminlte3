@extends('layouts.master')
@section('title')
    {{ trans('global.create') }} {{ trans('global.navigator.title_singular') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item "><a href="/">Home</a></li>
    <li class="breadcrumb-item active">{{ trans('global.create') }} {{ trans('global.navigator.title_singular') }}</li>
    <li class="breadcrumb-item "> <i class="fas fa-question-circle"></i></li>
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route("navigators.store") }}" method="POST" enctype="multipart/form-data">
            @include ('navigators.form', [
                'navigator' => new App\Navigator,
                'buttonText' => trans('global.create').' '. trans('global.navigator.title_singular')
            ])
        </form>
    </div>
</div>
@endsection