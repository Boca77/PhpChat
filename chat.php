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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --whatsapp-green: #128C7E;
      --whatsapp-light-green: #25D366;
      --whatsapp-chat-bg: #E5DDD5;
      --whatsapp-sent: #DCF8C6;
      --whatsapp-received: #FFFFFF;
    }

    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--whatsapp-green);
      border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--whatsapp-light-green);
    }

    body {
      background-color: #f0f2f5;
    }

    #messages {
      height: calc(100vh - 220px);
      overflow-y: auto;
      display: flex;
      flex-direction: column-reverse;
      background-color: var(--whatsapp-chat-bg);
      background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAABhGlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV/TSlUqDnYQcchQnSyIigguUsUiWChthVYdTG76giYNSYqLo+BacPBjserg4qyrg6ugCPgBcXRyUnSREv+XFFrEenDcj3f3HnfvAKFWYprVMQZoum2mEnExk10RQ68IIoQ+9GJQZpYxK0lJ+I6vewj4ehfjWf5n/699NHtmgUBAJJ5hhum0iTiRXNrY2swqJ94jDrOKovE9ccKgSID8yKjL54zHDbvwzDEz6WaT44ixxIJCG8ttzIqGSjxFHFFUjfKFrMsK5y3OarnKmvfkLwzltJVlrtMcRAILWIQEEQqqKKEMGzFadVIspGg/5uEfdvxJcsnkKoGRYwEVqJBcP/gf/O7WzE+Mu0nhOND+YtsfI0BgF6hXbfv72LbrJ4D/GbjSmv5yDZj5JL3e1MLYB/RuAxfXTU3ZAy53gIEnQzZlR/LTFPB+Bd0zGr4FuNYNXnN7b87jdA9AUFu1TN8DHg6A4SJlr3u8u6u9b//WNOf3A0BQcqeQBmFvAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsMAAALDAE/QCLIAAAACXZwQWcAAABkAAAAZAB4kex3AAAAB3RJTUUH4QgFCCUB43+3eQAAABl0RVh0Q29tbWVudABDcmVhdGVkIHdpdGggR0lNUFeBDhcAAACLSURBVHja7dAxAQAADMOg+TfdGYgDpJNXwYJMAkkgCSQJJBBIAkkgSQIJBJJAkkCSQAKBJJAEkiSQQCAJJIEkCSD5kt0EYjeBJJBAIAkkgSQBpgoEkEASBhkGEQYRBhEGAQQQQAABBBBAAAEEEEAAAQQQQAABBBBAAAEEEEAAAQQQQAABBBBAAAEEkPcbR8fWPqTbL3YAAAAASUVORK5CYII=");
    }

    .message-bubble {
      max-width: 70%;
      word-wrap: break-word;
      position: relative;
      padding: 8px 12px;
    }

    .message-sent .message-bubble {
      background-color: var(--whatsapp-sent);
      color: #303030;
      border-radius: 12px 0 12px 12px;
    }

    .message-sent .message-bubble::before {
      content: "";
      position: absolute;
      top: 0;
      right: -8px;
      width: 0;
      height: 0;
      border-left: 8px solid var(--whatsapp-sent);
      border-right: 0 solid transparent;
      border-top: 8px solid transparent;
    }

    .message-received .message-bubble {
      background-color: var(--whatsapp-received);
      border-radius: 0 12px 12px 12px;
    }

    .message-received .message-bubble::before {
      content: "";
      position: absolute;
      top: 0;
      left: -8px;
      width: 0;
      height: 0;
      border-right: 8px solid var(--whatsapp-received);
      border-left: 0 solid transparent;
      border-top: 8px solid transparent;
    }

    .message-time {
      font-size: 0.7rem;
      color: #667781;
      margin-top: 2px;
    }

    .pointer {
      cursor: pointer;
    }

    /* WhatsApp-like sidebar styling */
    .col-3 {
      background-color: #ffffff !important;
      border-right: 1px solid #e9edef !important;
    }

    .settings-menu {
      top: 40px;
    }

    .no-select {
      user-select: none;
    }

    /* WhatsApp-like header */
    .chat-header {
      background-color: #f0f2f5;
      border-bottom: 1px solid #e9edef !important;
    }

    /* WhatsApp-like input area */
    #messageInput {
      background-color: #f0f2f5;
      padding: 10px;
      border-radius: 8px;
    }

    #message {
      border-radius: 8px;
      border: 1px solid #e9edef;
      padding: 9px 12px;
      font-size: 15px;
    }

    #sendBtn {
      background-color: var(--whatsapp-green);
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #sendBtn:hover {
      background-color: var(--whatsapp-light-green);
    }

    .chat-item:hover {
      background-color: #f0f2f5 !important;
    }

    .hover-bg-light:hover {
      background-color: #f0f2f5;
    }

    .input-group-text {
      border-color: #e9edef;
    }

    .form-control:focus {
      border-color: #e9edef;
      box-shadow: none;
    }

    .settings-btn {
      transition: background-color 0.2s;
    }    .settings-btn:hover {
      background-color: #e9edef;
      border-radius: 50%;
    }

    .add-friend-btn {
      padding: 6px;
      border-radius: 15px;
      transition: background-color 0.2s;
    }

    .add-friend-btn:hover {
      background-color: #f0f2f5;
    }

    /* Modal styles */
    .modal-content {
      border: none;
      border-radius: 12px;
    }

    .modal-header {
      background-color: var(--whatsapp-green);
      color: white;
      border-bottom: none;
      border-radius: 12px 12px 0 0;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1);
    }

    .user-item {
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .user-item:hover {
      background-color: #f0f2f5;
    }

    .add-user-btn {
      background-color: var(--whatsapp-green);
      border: none;
      opacity: 0.9;
      transition: opacity 0.2s;
    }

    .add-user-btn:hover {
      background-color: var(--whatsapp-green);
      opacity: 1;
    }

    #searchUsers {
      background-color: #f0f2f5;
    }
  </style>
</head>

<body>
    <div class="d-flex vh-100">        <div class="col-3 p-0" style="background-color: #ffffff; border-right: 1px solid #e9edef;">
            <!-- User Profile Header -->
            <div class="d-flex align-items-center p-2" style="background-color: #f0f2f5; height: 60px;">
                <div class="d-flex align-items-center flex-grow-1">
                    <img src="https://picsum.photos/200" class="rounded-circle me-3" style="width: 40px;" alt="">
                    <div class="d-flex flex-column">
                        <p class="fw-semibold mb-0"><?= $user['name'] . ' ' . $user['surname'] ?></p>
                        <small class="text-muted" id="timer">00:00:00</small>
                    </div>
                </div>
                <div class="settings-container position-relative ms-2">
                    <button class="btn p-1 settings-btn">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="#54656f">
                            <path d="M12 7a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 7zm0 2a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 9zm0 6a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 15z"></path>
                        </svg>
                    </button>
                    <div class="settings-menu position-absolute bg-white shadow rounded p-2 d-none" style="right: 0; min-width: 180px; z-index: 1000;">
                        <div class="pointer p-2 rounded hover-bg-light">
                            <small class="fw-semibold text-dark">My Profile</small>
                        </div>
                        <form action="logout.php" method="post" id="logout">
                            <div class="pointer p-2 rounded hover-bg-light" id="logoutBtn">
                                <small class="fw-semibold text-dark">Logout</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="p-2" style="background-color: #ffffff;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="color: #54656f;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M15.9 14.3H15l-0.3-0.3c1-1.1 1.6-2.7 1.6-4.3 0-3.7-3-6.7-6.7-6.7S3 6 3 9.7s3 6.7 6.7 6.7c1.6 0 3.2-0.6 4.3-1.6l0.3 0.3v0.8l5.1 5.1 1.5-1.5-5-5.2zm-6.2 0c-2.6 0-4.6-2.1-4.6-4.6s2.1-4.6 4.6-4.6 4.6 2.1 4.6 4.6-2 4.6-4.6 4.6z"></path>
                        </svg>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search or start new chat" style="background-color: #f0f2f5;">
                </div>
            </div>

            <!-- Chats List -->
            <div class="overflow-auto" style="height: calc(100vh - 120px);">
                <div class="chat-item d-flex align-items-center px-3 py-2 border-bottom pointer" style="background-color: #ffffff; transition: all 0.2s;">
                    <div class="me-3 position-relative">
                        <img src="https://picsum.photos/200" class="rounded-circle" style="width: 49px; height: 49px;" alt="">
                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 11px; height: 11px; border: 2px solid white;"></span>
                    </div>
                    <div class="flex-grow-1 border-bottom" style="border-color: #e9edef;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fw-semibold mb-0">User 1</p>
                            <small class="text-muted">12:00 PM</small>
                        </div>
                        <p class="text-muted small mb-0 text-truncate">Last message preview...</p>
                    </div>
                </div>
            </div>
        </div><div class="col-9 d-flex flex-column">
            <div class="chat-header d-flex align-items-center px-4 py-2">
                <img id="friendImage" src="https://picsum.photos/200" class="rounded-circle me-3" style="width: 40px;" alt="">                <div>
                    <p class="fw-semibold mb-0" id="friendName">User</p>
                    <small class="text-muted">online</small>
                </div>                <button class="btn ms-auto add-friend-btn d-flex align-items-center gap-2" title="Add Friend" data-bs-toggle="modal" data-bs-target="#addFriendModal">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="#54656f">
                        <path d="M13 14.062V22H4a8 8 0 0 1 9-7.938zM12 13c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5zm6 6v-2h2v-2h-2v-2h-2v2h-2v2h2v2h2z"/>
                    </svg>
                    <span class="d-none d-md-inline text-muted small">Add Friend</span>
                </button>
            </div>

            <div class="flex-grow-1 d-flex flex-column p-3">
                <div id="messages" class="rounded mb-3">
                    <div class="message-sent mb-3">
                        <div class="d-flex flex-column align-items-end">
                            <div class="message-bubble">
                                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Incidunt, facilis?</p>
                                <small class="message-time d-block text-end">12:00 PM</small>
                            </div>
                        </div>
                    </div>

                    <div class="message-received mb-3">
                        <div class="d-flex flex-column align-items-start">
                            <div class="message-bubble">
                                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Incidunt, facilis?</p>
                                <small class="message-time d-block">12:01 PM</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="messageInput" class="d-flex gap-2">
                    <input id="message" type="text" class="form-control" placeholder="Type a message">
                    <button id="sendBtn" class="btn text-white pointer">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                            <path d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Friend Modal -->
    <div class="modal fade" id="addFriendModal" tabindex="-1" aria-labelledby="addFriendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFriendModalLabel">Add New Friend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="#54656f">
                                <path d="M15.9 14.3H15l-0.3-0.3c1-1.1 1.6-2.7 1.6-4.3 0-3.7-3-6.7-6.7-6.7S3 6 3 9.7s3 6.7 6.7 6.7c1.6 0 3.2-0.6 4.3-1.6l0.3 0.3v0.8l5.1 5.1 1.5-1.5-5-5.2zm-6.2 0c-2.6 0-4.6-2.1-4.6-4.6s2.1-4.6 4.6-4.6 4.6 2.1 4.6 4.6-2 4.6-4.6 4.6z"></path>
                            </svg>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchUsers" placeholder="Search users...">
                    </div>
                    <div class="users-list">
                        <!-- Placeholder users -->
                        <div class="user-item d-flex align-items-center p-2 rounded mb-2" style="transition: all 0.2s;">
                            <img src="https://picsum.photos/200?1" class="rounded-circle me-3" style="width: 48px; height: 48px;" alt="">
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <small class="text-muted">Active now</small>
                            </div>
                            <button class="btn btn-success btn-sm ms-auto add-user-btn">Add</button>
                        </div>
                        <div class="user-item d-flex align-items-center p-2 rounded mb-2">
                            <img src="https://picsum.photos/200?2" class="rounded-circle me-3" style="width: 48px; height: 48px;" alt="">
                            <div>
                                <h6 class="mb-0">Jane Smith</h6>
                                <small class="text-muted">Last seen 2h ago</small>
                            </div>
                            <button class="btn btn-success btn-sm ms-auto add-user-btn">Add</button>
                        </div>
                        <div class="user-item d-flex align-items-center p-2 rounded mb-2">
                            <img src="https://picsum.photos/200?3" class="rounded-circle me-3" style="width: 48px; height: 48px;" alt="">
                            <div>
                                <h6 class="mb-0">Mike Johnson</h6>
                                <small class="text-muted">Active now</small>
                            </div>
                            <button class="btn btn-success btn-sm ms-auto add-user-btn">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
        $(document).ready(function() {
            // Timer functionality
            let startTime = new Date();
            
            function updateTimer() {
                let currentTime = new Date();
                let timeDiff = new Date(currentTime - startTime);
                let hours = timeDiff.getUTCHours().toString().padStart(2, '0');
                let minutes = timeDiff.getUTCMinutes().toString().padStart(2, '0');
                let seconds = timeDiff.getUTCSeconds().toString().padStart(2, '0');
                $('#timer').text(`Time: ${hours}:${minutes}:${seconds}`);
            }

            // Update timer every second
            setInterval(updateTimer, 1000);

            // Settings menu functionality
            $('.settings-btn').on('click', function() { 
                $('.settings-menu').toggleClass('d-none'); 
            });

            $('#logoutBtn').on('click', function() {
                $('#logout').submit();
            });

            // Add Friend functionality
            $('.add-friend-btn').on('click', function() {
                $('#addFriendModal').modal('show');
            });

            // User search functionality
            $('#searchUsers').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.user-item').each(function() {
                    const userName = $(this).find('h6').text().toLowerCase();
                    if (userName.startsWith(searchTerm)) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            // Add user button click handler
            $('.add-user-btn').on('click', function(e) {
                e.stopPropagation();
                const $btn = $(this);
                $btn.prop('disabled', true)
                   .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                // Simulate adding friend (replace with actual API call)
                setTimeout(() => {
                    $btn.html('Added')
                       .removeClass('btn-success')
                       .addClass('btn-secondary');
                }, 1000);
            });

            $.ajax({
                url: 'apis/users.php',
                type: 'POST',
                dataType: 'json',
                data: { mode: 'getUsers' },
                success: function(data) {                    console.log(data);
                    // Clear existing placeholder users
                    $(".users-list").empty();
                    
                    data.forEach(user => {
                        $(".users-list").append(`
                        <div class="user-item d-flex align-items-center p-2 rounded mb-2">
                            <img src="https://picsum.photos/200?${user.id}" class="rounded-circle me-3" style="width: 48px; height: 48px;" alt="">
                            <div>
                                <h6 class="mb-0">${user.name} ${user.surname}</h6>
                                <small class="text-muted">${user.email}</small>
                            </div>
                            <button class="btn btn-success btn-sm ms-auto add-user-btn">Add</button>
                        </div>`)
                            
                    })
                    
                },
                error: function() {
                    console.error('Error fetching user data');
                }
            })
        });
  </script>
</body>

</html>
