<?php
include_once 'Classes/User.php';

session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php?error=Not logged in');
}

$stmt = new User($_SESSION['userId']);
$user = $stmt->getUser();

if (!$user) {
    header('Location: login.php?error=Invalid user');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dekić's Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --whatsapp-green: #128C7E;
            --whatsapp-light-green: #25D366;
        }

        body {
            background-color: #f0f2f5;
        }

        .profile-header {
            background: linear-gradient(to bottom, #128C7E, #075E54);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAABhGlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV/TSlUqDnYQcchQnSyIigguUsUiWChthVYdTG76giYNSYqLo+BacPBjserg4qyrg6ugCPgBcXRyUnSREv+XFFrEenDcj3f3HnfvAKFWYprVMQZoum2mEnExk10RQ68IIoQ+9GJQZpYxK0lJ+I6vewj4ehfjWf5n/699NHtmgUBAJJ5hhum0iTiRXNrY2swqJ94jDrOKovE9ccKgSID8yKjL54zHDbvwzDEz6WaT44ixxIJCG8ttzIqGSjxFHFFUjfKFrMsK5y3OarnKmvfkLwzltJVlrtMcRAILWIQEEQqqKKEMGzFadVIspGg/5uEfdvxJcsnkKoGRYwEVqJBcP/gf/O7WzE+Mu0nhOND+YtsfI0BgF6hXbfv72LbrJ4D/GbjSmv5yDZj5JL3e1MLYB/RuAxfXTU3ZAy53gIEnQzZlR/LTFPB+Bd0zGr4FuNYNXnN7b87jdA9AUFu1TN8DHg6A4SJlr3u8u6u9b//WNOf3A0BQcqeQBmFvAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsMAAALDAE/QCLIAAAACXZwQWcAAABkAAAAZAB4kex3AAAAB3RJTUUH4QgFCCUB43+3eQAAABl0RVh0Q29tbWVudABDcmVhdGVkIHdpdGggR0lNUFeBDhcAAACLSURBVHja7dAxAQAADMOg+TfdGYgDpJNXwYJMAkkgCSQJJBBIAkkgSQIJBJJAkkCSQAKBJJAEkiSQQCAJJIEkCSD5kt0EYjeBJJBAIAkkgSQBpgoEkEASBhkGEQYRBhEGAQQQQAABBBBAAAEEEEAAAQQQQAABBBBAAAEEEEAAAQQQQAABBBBAAAEEkPcbR8fWPqTbL3YAAAAASUVORK5CYII=');
            opacity: 0.1;
        }

        .profile-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 4px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .info-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .info-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .back-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .online-status {
            font-size: 1rem;
            color: white;
            margin-top: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(37, 211, 102, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #25D366;
            border-radius: 50%;
            position: relative;
        }

        .status-dot::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background-color: #25D366;
            border-radius: 50%;
            animation: pulse 2s infinite;
            opacity: 0.5;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.5; }
            70% { transform: scale(2); opacity: 0; }
            100% { transform: scale(1); opacity: 0; }
        }
    </style>
</head>

<body>    <div class="profile-header text-center">
        <div class="container">            <a href="/PhpChat/chat.php" class="back-btn float-start">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Chat
            </a>
            <div class="py-4">
                <img src="dekic.jpg" alt="Dekić's Profile Picture" class="profile-img mb-3">
                <h2 class="mb-0">Dekić</h2>
                <div class="online-status">
                    <span class="status-dot"></span>
                    Online
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="info-card">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">Dekić</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Status</div>
                            <div class="info-value">🎮 Gaming and Coding</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Last Seen</div>
                            <div class="info-value">Online</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Member Since</div>
                            <div class="info-value">May 2025</div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h5 class="mb-3">About</h5>
                    <p class="mb-0">Tech enthusiast and coding aficionado. Always up for a good chat about programming or gaming!</p>
                </div>

                <div class="info-card">
                    <h5 class="mb-3">Contact Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Email</div>
                            <div class="info-value">dekic@example.com</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Phone</div>
                            <div class="info-value">+123 456 789</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>