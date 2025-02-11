<?php
// backend.php
// Start the WebSocket server
$host = '127.0.0.1';
$port = 8080;
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);
$clients = [];

while (true) {
    $read = $clients;
    $read[] = $socket;
    socket_select($read, $write, $except, null);

    if (in_array($socket, $read)) {
        $clients[] = socket_accept($socket);
        $read = array_diff($read, [$socket]);
    }

    foreach ($read as $client) {
        $data = @socket_read($client, 1024);
        if (!$data) {
            $clients = array_diff($clients, [$client]);
            continue;
        }

        $request = json_decode($data, true);

        if ($request['action'] === 'fetch_users') {
            $users = getUsersFromDatabase();
            sendToClient($client, ['action' => 'users', 'data' => $users]);
        } elseif ($request['action'] === 'block_user') {
            $userId = $request['user_id'];
            blockUserInDatabase($userId);
            sendToAllClients($clients, ['action' => 'blocked', 'user_id' => $userId]);
        }
    }
}

function sendToClient($client, $data) {
    socket_write($client, json_encode($data));
}

function sendToAllClients($clients, $data) {
    foreach ($clients as $client) {
        sendToClient($client, $data);
    }
}

function getUsersFromDatabase() {
    $pdo = new PDO('mysql:host=localhost;dbname=data', 'root', '');
    $stmt = $pdo->query('SELECT id, nickname, complaint FROM data.user WHERE blocked = 0');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function blockUserInDatabase($userId) {
    $pdo = new PDO('mysql:host=localhost;dbname=data', 'root', '');
    $stmt = $pdo->prepare('UPDATE data.user SET blocked = 1 WHERE id = ?');
    $stmt->execute([$userId]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        .dashboard {
            width: 80%;
            max-width: 1200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #6200ea;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
        }

        .user-list {
            max-height: 500px;
            overflow-y: auto;
            padding: 20px;
        }

        .user {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .user:last-child {
            border-bottom: none;
        }

        .user span {
            flex: 1;
        }

        .user button {
            background: #e53935;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .user button:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">Admin Dashboard</div>
        <div class="user-list" id="userList">
            <!-- Users will be dynamically loaded here -->
        </div>
    </div>

    <script>
        const ws = new WebSocket('ws://127.0.0.1:8080');

        ws.onopen = () => {
            ws.send(JSON.stringify({ action: 'fetch_users' }));
        };

        ws.onmessage = (message) => {
            const response = JSON.parse(message.data);

            if (response.action === 'user_list') {
                const userList = document.getElementById('userList');
                userList.innerHTML = '';

                response.data.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.className = 'user';

                    userElement.innerHTML = `
                        <span>${user.nickname}</span>
                        <span>${user.complaint}</span>
                        <button onclick="blockUser(${user.id})">Block</button>
                    `;

                    userList.appendChild(userElement);
                });
            } else if (response.action === 'user_blocked') {
                alert(`User with ID ${response.user_id} has been blocked.`);
                ws.send(JSON.stringify({ action: 'fetch_users' }));
            }
        };

        function blockUser(userId) {
            ws.send(JSON.stringify({ action: 'block_user', user_id: userId }));
        }
    </script>
</body>
</html>
