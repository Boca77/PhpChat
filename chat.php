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
    ::-webkit-scrollbar {
      width: 5px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #C4DFDF;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    #messages {
      height: 90%;
      overflow-y: auto;
      display: flex;
      flex-direction: column-reverse;
    }

    .pointer{
      cursor: pointer;
    }

    .settings-menu{
        top: 40px
    }

    .no-select{
      user-select: none;
    }
  </style>
</head>

<body>
    <div class="d-flex vh-100">
        <div class="col-3 bg-info-subtle border-end border-4 border-info p-0">
            <div class="d-flex align-items-center border-bottom border-info border-4 p-3">
                <div class="me-3">
                    <img src="https://picsum.photos/200" class="rounded-circle img-fluid" style="width: 80px;" alt="">
                </div>
                <div>
                    <p class="fw-semibold fs-5 mb-1"><?= $user['name'] . ' ' . $user['surname']  ?></p>
                    <div class="settings-container position-relative">
                        <p class="mb-0 text-white bg-dark rounded px-2 py-1 text-center pointer settings-btn no-select">Settings</p>
                        <div class="settings-menu position-absolute bg-light border rounded p-3 d-none">
                            <p class="mb-0 pointer no-select">My Profile</p>
                            <form action="logout.php" method="post" id="logout">
                                <p class="mb-0 pointer no-select" id="logoutBtn">Logout</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-auto h-75 border-end">
                <h5 class="px-3 mt-4 mb-3 fw-bold">Messages</h5>
                <div class="d-flex align-items-center border-bottom border-info px-3 py-2">
                    <div class="me-3">
                        <img src="https://picsum.photos/200" class="rounded-circle img-fluid" style="width: 50px;" alt="">
                    </div>
                    <div>
                        <p class="fw-semibold fs-6 mb-0">User 1</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-9 d-flex flex-column">
            <div class="d-flex align-items-center border-bottom border-info border-4 px-4 py-3">
                <img id="friendImage" src="https://picsum.photos/200" class="rounded-circle me-3" style="width: 60px;" alt="">
                <p class="fw-semibold fs-5 mb-0" id="friendName">User</p>
            </div>

            <div class="flex-grow-1 bg-secondary-subtle d-flex flex-column p-3">
                <div id="messages" class="bg-white rounded p-3 mb-3">
                    <div id="messageSent" class="mb-3">
                        <p>User</p>
                        <div class="bg-light border rounded p-3 d-inline-block">
                            <p class="small text-muted mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Incidunt, facilis?</p>
                        </div>
                    </div>

                    <div id="messageReceived" class="d-flex justify-content-end mb-3">
                        <div>
                            <p class="text-end">User</p>
                            <div class="bg-light border rounded p-3 d-inline-block">
                                <p class="small text-muted mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Incidunt, facilis?</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="messageInput" class="d-flex gap-3">
                    <input id="message" type="text" class="form-control p-3 rounded" placeholder="Message">
                    <button id="sendBtn" class="btn btn-info px-4 py-2 text-white pointer">Send</button>
                </div>
            </div>
        </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
        $(document).ready(function() {
            $('.settings-btn').on('click', function() { 
                console.log('click');
                
                $('.settings-menu').toggleClass('d-none'); 
            })

            $('#logoutBtn').on('click', function() {
                $('#logout').submit();
            })
        })
        
    </script>
</body>

</html>
