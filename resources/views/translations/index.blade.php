@extends('layouts.app')

@section('title', __('lang_v1.design') . ' - ' . __('lang_v1.language'))

@section('content')
<section class="content-header">
    <h1>@lang('lang_v1.language') - বাংলা</h1>
</section>

<section class="content">
    @if(session('status'))
        @php $status = session('status'); @endphp
        @if(!empty($status['success']))
            <div class="alert alert-success">{{ $status['msg'] ?? __('messages.success') }}</div>
        @else
            <div class="alert alert-danger">{{ $status['msg'] ?? __('messages.something_went_wrong') }}</div>
        @endif
    @endif

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('lang_v1.add') / @lang('lang_v1.update') @lang('lang_v1.language')</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['url' => route('translations.store'), 'method' => 'post']) !!}
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            {!! Form::label('type', 'Type:*') !!}
                            {!! Form::select('type', ['php' => 'PHP (namespaced keys)', 'json' => 'JSON (raw strings)'], 'php', ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            {!! Form::label('key', 'Key:*') !!}
                            {!! Form::text('key', null, ['class' => 'form-control', 'required', 'placeholder' => 'e.g. custom_greeting']) !!}
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            {!! Form::label('value', 'Bangla Value:*') !!}
                            {!! Form::text('value', null, ['class' => 'form-control', 'required', 'placeholder' => 'e.g. স্বাগতম']) !!}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">@lang('messages.save')</button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('lang_v1.current') @lang('lang_v1.language')</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Bangla</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($translations ?? []) as $k => $v)
                            <tr>
                                <td><code>{{ $k }}</code></td>
                                <td>{{ $v }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No custom translations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">JSON Translations (raw strings)</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Raw String</th>
                            <th>Bangla</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($jsonTranslations ?? []) as $k => $v)
                            <tr>
                                <td><code>{{ $k }}</code></td>
                                <td>{{ $v }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No JSON translations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection


