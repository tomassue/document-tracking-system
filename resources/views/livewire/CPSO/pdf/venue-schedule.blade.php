<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>pdf</title>
</head>

<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('myimage.png') }}" alt="image" width="200" />
            </td>
            <td class="w-half">
                <h2>pdf ID: test523</h2>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="details">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            <tr>
                <td class="w-half">
                    <div>
                        <h4>To:</h4>
                    </div>
                    <div>{{ $name }}</div>
                    <div>{{ $email }}</div>
                    <div>{{ $phone}}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer margin-top">
        <div>Thank you</div>
    </div>
</body>

</html>