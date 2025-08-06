<?php
session_start(); // Start the session

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
include 'db.php';

$message = "";
$message_type = "";

// Check for messages from other pages (e.g., delete_user.php, edit.php, add_user.php)
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'success';
}

// --- Fetch Registered Users ---
$registered_users = [];
$sql_users = "SELECT id, name, email, created_at FROM users ORDER BY name ASC";
$result_users = $conn->query($sql_users);

if ($result_users) {
    if ($result_users->num_rows > 0) {
        while ($row_user = $result_users->fetch_assoc()) {
            $registered_users[] = $row_user;
        }
    }
} else {
    $message .= "Error fetching users: " . $conn->error . "<br>";
    $message_type = 'error';
}


// --- Fetch Biodata Entries ---
$biodata = []; // Initialize an empty array to hold biodata records
$sql_biodata = "SELECT * FROM biodata ORDER BY name ASC"; // Order by name for better readability
$result_biodata = $conn->query($sql_biodata);

if ($result_biodata) {
    if ($result_biodata->num_rows > 0) {
        while ($row_biodata = $result_biodata->fetch_assoc()) {
            $biodata[] = $row_biodata;
        }
    }
} else {
    $message .= "Error fetching biodata: " . $conn->error;
    $message_type = 'error';
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #34495e;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.8em;
            color: #2c3e50;
        }
        .header .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .header .user-info p {
            margin: 0;
            font-weight: 600;
        }
        .btn {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }
        .btn-logout { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .btn-add { background: linear-gradient(135deg, #3498db, #2980b9); }
        .btn-add-user { background: linear-gradient(135deg, #1abc9c, #16a085); }
        
        .message-box {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .content-section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .section-header h2 {
            margin: 0;
            color: #2c3e50;
        }
        .item-list {
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }
        .card {
            border: 1px solid #ecf0f1;
            padding: 20px;
            border-radius: 12px;
            background-color: #fdfdfd;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
        }
        .card h4 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #34495e;
            font-size: 1.2em;
        }
        .card p {
            margin: 8px 0;
            color: #7f8c8d;
        }
        .card p strong {
            color: #5d6d7e;
        }
        .card img {
            max-width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid #ecf0f1;
        }
        .btn-group { margin-top: 15px; display: flex; gap: 10px; }
        .btn-group a {
            padding: 8px 15px;
            font-size: 0.9em;
            text-decoration: none;
            color: #fff;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }
        .btn-group a:hover { opacity: 0.9; }
        .btn-group .edit { background-color: #3498db; }
        .btn-group .delete { background-color: #e74c3c; }
        
        /* Modal styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex; justify-content: center; align-items: center;
            z-index: 1000; visibility: hidden; opacity: 0; transition: visibility 0s, opacity 0.3s;
        }
        .modal-overlay.active { visibility: visible; opacity: 1; }
        .modal-content {
            background: #fff; padding: 30px; border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); text-align: center;
            max-width: 400px; width: 90%;
        }
        .modal-content h3 { margin-top: 0; color: #c0392b; }
        .modal-content p { margin-bottom: 25px; }
        .modal-buttons button, .modal-buttons a {
            padding: 12px 25px; border-radius: 8px; font-weight: 600;
        }
        a.confirm-delete { background: #e74c3c; margin-right: 10px; }
        button.cancel-delete { background: #bdc3c7; border: none; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Dashboard</h1>
            <div class="user-info">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="message-box <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="content-section">
            <div class="section-header">
                <h2>Registered Users</h2>
                <a href="add_user.php" class="btn btn-add-user"><i class="fas fa-user-plus"></i> Add New User</a>
            </div>
            <?php if (empty($registered_users)): ?>
                <p>No registered users found.</p>
            <?php else: ?>
                <div class="item-list">
                    <?php foreach ($registered_users as $user): ?>
                        <div class="card">
                            <h4><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></h4>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
                            <p><strong>Registered:</strong> <?php echo date("d M, Y", strtotime($user['created_at'] ?? 'now')); ?></p>
                            <div class="btn-group">
                                <a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="#" class="delete" onclick="showDeleteModal('delete_user.php?id=<?php echo htmlspecialchars($user['id']); ?>', 'Are you sure you want to delete the user <?php echo htmlspecialchars(addslashes($user['name'])); ?>?'); return false;"><i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>Biodata Entries</h2>
                <a href="create.php" class="btn btn-add"><i class="fas fa-plus-circle"></i> Create New Biodata</a>
            </div>
            <?php if (empty($biodata)): ?>
                <p>No biodata entries found.</p>
            <?php else: ?>
                <div class="item-list">
                    <?php foreach ($biodata as $entry): ?>
                        <div class="card">
                            <?php if (!empty($entry['photo_path']) && file_exists($entry['photo_path'])): ?>
                                <img src="<?php echo htmlspecialchars($entry['photo_path']); ?>" alt="Profile Photo">
                            <?php else: ?>
                                <img src="placeholder.png" alt="No Photo"> <?php endif; ?>
                            <h4><?php echo htmlspecialchars($entry['name'] ?? 'N/A'); ?></h4>
                            <p><strong>ID:</strong> <?php echo htmlspecialchars($entry['id'] ?? 'N/A'); ?></p>
                            <p><strong>Occupation:</strong> <?php echo htmlspecialchars($entry['occupation'] ?? 'N/A'); ?></p>
                             <p><strong>Contact:</strong> <?php echo htmlspecialchars($entry['contact_number'] ?? 'N/A'); ?></p>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo htmlspecialchars($entry['id']); ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="#" class="delete" onclick="showDeleteModal('delete2.php?id=<?php echo htmlspecialchars($entry['id']); ?>', 'Are you sure you want to delete the biodata for <?php echo htmlspecialchars(addslashes($entry['name'])); ?>?'); return false;"><i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
            <p id="modal-message">Are you sure?</p>
            <div class="modal-buttons">
                <a id="confirmDeleteButton" class="btn confirm-delete" href="#">Delete</a>
                <button class="btn cancel-delete" onclick="hideDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(url, message) {
            document.getElementById('modal-message').textContent = message;
            document.getElementById('confirmDeleteButton').href = url;
            document.getElementById('deleteModal').classList.add('active');
        }
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>