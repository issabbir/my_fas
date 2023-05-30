<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৮ PM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    <h3 class="text-danger"><span class="bx bx-info-circle"></span>{{$message}}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')

@endsection
