<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>


<body {{ $mode }}_mode>

@if($mode !== 'iframe')
@include('partials/header_nav')
@endif

<div id="wrapper">
