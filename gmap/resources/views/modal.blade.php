<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Geo Location</title>
    <link rel="stylesheet" href="{{ url(elixir("css/all.css")) }}">
    <script>var baseUrl = "{{ url('/') }}";</script>
</head>
<body>

<!-- Large modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>
{{--<a href="map" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#myModal">Launch Demo Modal</a>--}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Get Location</h4>
            </div>
            <div class="modal-body">
                <iframe src="map" height="760" width="100%" frameborder="0"></iframe>
                {{--@include('map')--}}
            </div>
        </div>
    </div>
</div>
<script src="{{ url(elixir("js/all.js")) }}"></script>
<!-- Latest compiled and minified JavaScript -->
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>--}}
</body>
</html>