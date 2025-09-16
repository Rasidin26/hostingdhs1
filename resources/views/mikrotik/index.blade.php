<!DOCTYPE html>
<html>
<head>
    <title>Data Hotspot Users</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Data Hotspot Users</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Password</th>
                <th>Profile</th>
                <th>Uptime</th>
                <th>Bytes In</th>
                <th>Bytes Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user['name'] ?? '-' }}</td>
                    <td>{{ $user['password'] ?? '-' }}</td>
                    <td>{{ $user['profile'] ?? '-' }}</td>
                    <td>{{ $user['uptime'] ?? '-' }}</td>
                    <td>{{ $user['bytes-in'] ?? '-' }}</td>
                    <td>{{ $user['bytes-out'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
