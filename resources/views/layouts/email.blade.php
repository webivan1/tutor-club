<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td>
                    @yield('content')
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
