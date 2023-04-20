<?php
require_once('session.php');
require_once('db.php');

session_start();

// Redirect user to login page if not logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once('db.php');

// Get user's username from database
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle account balance form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $balance = $_POST['balance'];
  $stmt = $pdo->prepare('INSERT INTO account_balances (user_id, balance) VALUES (:user_id, :balance)');
  $stmt->execute(['user_id' => $_SESSION['user_id'], 'balance' => $balance]);
}

// Get user's account balances from database
$stmt = $pdo->prepare('SELECT * FROM account_balances WHERE user_id = :user_id ORDER BY id DESC');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$balances = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
  <link rel="stylesheet" href="style3.css">
  <style>
    /* Style for the modal box */
		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgba(0, 0, 0, 0.4);
			padding-top: 50px;
		}
		
		/* Style for the modal content */
		.modal-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 80%;
		}
		
		/* Style for the close button */
		.close {
			color: #aaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}
		
		.close:hover,
		.close:focus {
			color: black;
			text-decoration: none;
			cursor: pointer;
		}
  </style>
</head>
<body>
<div class="app-container">
    <div class="app-header">
        <div class="app-header-left">
            <span class="app-icon"></span>
            <p class="app-name">YUSUFII</p>
        </div>
        <div class="app-header-right">
            <button class="mode-switch" title="Switch Theme">
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
            <button onclick="openModal()" class="add-btn" title="Send Peyyer">
                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-plus">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
            </button>
            <button class="profile-btn">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrKZKH0HSRo1WXrWWmrz68RM64Q_5vtrQ6VA&usqp=CAU" />
                <a href="delete.php"><span>
                        <?php echo $user['username']; ?>
                    </span></a>
            </button>
        </div>
    </div>
    <div class="app-content">
        <div class="app-sidebar">
            <a href="" class="app-sidebar-link active">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-home">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </a>
            <a href="logout.php" class="app-sidebar-link">
                <svg class="link-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    class="feather feather-settings" viewBox="0 0 24 24">
                    <defs />
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                </svg>
            </a>
        </div>
        <div class="project-box-wrapper">
            <div class="project-box" style="background-color: #fee4cb;">
                <div class="project-box-header">
                    <span>
                        <?php
            $month = date('F'); // Sets $month to the current month's full name
            $day = date('j'); // Sets $day to the current day of the month
            $year = date('Y'); // Sets $year to the current year
            echo "{$year} {$month} {$day}"; // Outputs something like "Today is April 20"
            ?>
                    </span>
                </div>
                <div class="project-box-content-header">
                    <p class="box-content-header">
                        <?php echo $user['username']; ?>
                    </p>
                    <p class="box-content-subheader">YSFC</p>
                </div>
                <div class="box-progress-wrapper">
                    <p class="box-progress-header">Balance</p>
                    <div class="box-progress-bar">
                        <span class="box-progress" style="width: 60%; background-color: #ff942e"></span>
                    </div>
                    <p class="box-progress-percentage">60%</p>
                </div>
                <div class="project-box-footer">
                    <div class="days-left" style="color: #ff942e;">
                        36765.98£
                    </div>
                </div>
            </div>
        </div>
        <div class="projects-section">

            <div class="projects-section-header">
                <p>Wallet</p>
                <p class="time">
                    <?php
            $month = date('F'); // Sets $month to the current month's full name
            $day = date('j'); // Sets $day to the current day of the month
            echo "{$month} {$day}"; // Outputs something like "Today is April 20"
            ?>
                </p>
            </div>
            <div class="projects-section-line">
                <div class="projects-status">
                    <div class="item-status">
                        <span class="status-number">45</span>
                        <span class="status-type">In Progress</span>
                    </div>
                    <div class="item-status">
                        <span class="status-number">24</span>
                        <span class="status-type">Upcoming</span>
                    </div>
                    <div class="item-status">
                        <span class="status-number">62</span>
                        <span class="status-type">Total Projects</span>
                    </div>
                </div>
                <div class="view-actions">
                    <button class="view-btn list-view" title="List View">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-list">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>
                    </button>
                    <button class="view-btn grid-view active" title="Grid View">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-grid">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="project-boxes jsGridView">

                <?php foreach ($balances as $balance): ?>
                <div class="project-box-wrapper">
                    <div class="project-box" style="background-color: #e9e7fd;">
                        <div class="project-box-header">
                            <span>
                                <?php echo $balance['created_at']; ?>
                            </span>
                        </div>
                        <div class="project-box-footer">
                            <div class="days-left" style="color: #4f3ff0;">
                                <?php echo $balance['balance']; ?> £
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="balance">Balance:</label>
            <input type="number" name="balance" id="balance">
            <button type="submit">Pay</button>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modeSwitch = document.querySelector('.mode-switch');

        modeSwitch.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            modeSwitch.classList.toggle('active');
        });

        var listView = document.querySelector('.list-view');
        var gridView = document.querySelector('.grid-view');
        var projectsList = document.querySelector('.project-boxes');

        listView.addEventListener('click', function () {
            gridView.classList.remove('active');
            listView.classList.add('active');
            projectsList.classList.remove('jsGridView');
            projectsList.classList.add('jsListView');
        });

        gridView.addEventListener('click', function () {
            gridView.classList.add('active');
            listView.classList.remove('active');
            projectsList.classList.remove('jsListView');
            projectsList.classList.add('jsGridView');
        });

        document.querySelector('.messages-btn').addEventListener('click', function () {
            document.querySelector('.messages-section').classList.add('show');
        });

        document.querySelector('.messages-close').addEventListener('click', function () {
            document.querySelector('.messages-section').classList.remove('show');
        });
    });

    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementsByTagName("button")[0];

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    function openModal() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    function closeModal() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>
</body>
</html>