@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">File Uploader</div>
                <div class="card-body">
                 <form method="post" enctype="multipart/form-data" action="/upload_file">
                    <input type="file" name="file" />
                    <input type="submit" name="submit" value="Upload" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
