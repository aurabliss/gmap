<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<link rel="stylesheet" href="{{ url(elixir("css/all.css")) }}">
</head>

<body>
<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Link with href
</a>
<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Button with data-target
</button>
<div class="collapse" id="collapseExample">
    <div class="well">
        ...
    </div>
</div>
<script src="{{ url(elixir("js/all.js")) }}"></script>
</body>
</html>