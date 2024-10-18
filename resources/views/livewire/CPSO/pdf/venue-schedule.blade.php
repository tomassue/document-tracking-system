<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Venue Schedule</title>
</head>

<body>
    <h1>Venue Schedule</h1>
    @foreach($venues as $venue)
    <p>{{ $venue->venue }}</p> <!-- Using object property -->
    @endforeach
</body>

</html>