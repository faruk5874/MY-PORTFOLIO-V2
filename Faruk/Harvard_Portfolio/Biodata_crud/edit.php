<?php
session_start();

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
include 'db.php';

$message = "";
$biodata_entry = []; // Initialize to hold current biodata data

// Define the upload directory
$upload_dir = 'uploads/';

// Ensure the uploads directory exists and is writable (redundant, but good practice)
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

// Check if an ID is provided in the URL for editing
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);

    // Prepare a select statement to get the existing biodata data
    $sql_select = "SELECT * FROM biodata WHERE id = ?";

    if ($stmt_select = $conn->prepare($sql_select)) {
        $stmt_select->bind_param("i", $id);
        if ($stmt_select->execute()) {
            $result_select = $stmt_select->get_result();
            if ($result_select->num_rows == 1) {
                $biodata_entry = $result_select->fetch_assoc();
            } else {
                $message = "Error: No biodata entry found with that ID.";
            }
        } else {
            $message = "Error executing select statement: " . $stmt_select->error;
        }
        $stmt_select->close();
    } else {
        $message = "Error preparing select statement: " . $conn->error;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $id = $_POST['id'];

    // Collect and sanitize all input fields from the create form
    $photo_path = $_POST['existing_photo'] ?? ''; // Keep existing photo path by default
    $name = trim($_POST['name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $place_of_birth = trim($_POST['place_of_birth'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $height = trim($_POST['height'] ?? '');
    $marital_status = trim($_POST['marital_status'] ?? '');
    $religion = trim($_POST['religion'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $permanent_address = trim($_POST['permanent_address'] ?? '');
    $present_address = trim($_POST['present_address'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $father_occupation = trim($_POST['father_occupation'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $mother_occupation = trim($_POST['mother_occupation'] ?? '');
    $siblings = trim($_POST['siblings'] ?? '');
    $education_qualification = trim($_POST['education_qualification'] ?? '');
    $education_institute = trim($_POST['education_institute'] ?? '');
    $education_passing_year = trim($_POST['education_passing_year'] ?? '');
    $current_education = trim($_POST['current_education'] ?? '');
    $certifications = trim($_POST['certifications'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $annual_income = trim($_POST['annual_income'] ?? '');
    $career_plan = trim($_POST['career_plan'] ?? '');
    $complexion = trim($_POST['complexion'] ?? '');
    $body_type = trim($_POST['body_type'] ?? '');
    $diet = trim($_POST['diet'] ?? '');
    $smoking = trim($_POST['smoking'] ?? '');
    $drinking = trim($_POST['drinking'] ?? '');
    $hobbies = trim($_POST['hobbies'] ?? '');
    $partner_age_range = trim($_POST['partner_age_range'] ?? '');
    $partner_height = trim($_POST['partner_height'] ?? '');
    $partner_education = trim($_POST['partner_education'] ?? '');
    $partner_occupation = trim($_POST['partner_occupation'] ?? '');
    $partner_religion = trim($_POST['partner_religion'] ?? '');
    $about_me = trim($_POST['about_me'] ?? '');
    $future_plans = trim($_POST['future_plans'] ?? '');
    $health_issues = trim($_POST['health_issues'] ?? '');
    $languages = trim($_POST['languages'] ?? '');
    $preferred_location = trim($_POST['preferred_location'] ?? '');
    $social_media = trim($_POST['social_media'] ?? '');

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['photo']['tmp_name'];
        $file_name = $_FILES['photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_file_name = uniqid('photo_', true) . '.' . $file_ext;
        $destination = $upload_dir . $unique_file_name;

        $allowed_exts = array("jpg", "jpeg", "png", "gif");
        if (in_array($file_ext, $allowed_exts)) {
            if (move_uploaded_file($file_tmp_name, $destination)) {
                // If a new photo is uploaded, delete the old one
                if (!empty($photo_path) && file_exists($photo_path)) {
                    unlink($photo_path);
                }
                $photo_path = $destination;
            } else {
                $message = "Error uploading new photo.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    if (empty($message)) {
        // Update the biodata entry in the database
        $sql_update = "UPDATE biodata SET 
            photo_path=?, name=?, date_of_birth=?, place_of_birth=?, age=?, gender=?, height=?, marital_status=?, religion=?, nationality=?, blood_group=?, contact_number=?, email=?, permanent_address=?, present_address=?,
            father_name=?, father_occupation=?, mother_name=?, mother_occupation=?, siblings=?,
            education_qualification=?, education_institute=?, education_passing_year=?, current_education=?, certifications=?,
            occupation=?, annual_income=?, career_plan=?,
            complexion=?, body_type=?, diet=?, smoking=?, drinking=?, hobbies=?,
            partner_age_range=?, partner_height=?, partner_education=?, partner_occupation=?, partner_religion=?,
            about_me=?, future_plans=?, health_issues=?, languages=?, preferred_location=?, social_media=?
            WHERE id=?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("sssisssssssssssssssssisssssssssssssssssssssssi",
                $photo_path, $name, $date_of_birth, $place_of_birth, $age, $gender, $height, $marital_status, $religion, $nationality, $blood_group, $contact_number, $email, $permanent_address, $present_address,
                $father_name, $father_occupation, $mother_name, $mother_occupation, $siblings,
                $education_qualification, $education_institute, $education_passing_year, $current_education, $certifications,
                $occupation, $annual_income, $career_plan,
                $complexion, $body_type, $diet, $smoking, $drinking, $hobbies,
                $partner_age_range, $partner_height, $partner_education, $partner_occupation, $partner_religion,
                $about_me, $future_plans, $health_issues, $languages, $preferred_location, $social_media, $id);

            if ($stmt_update->execute()) {
                $message = "Biodata updated successfully!";
                header("Location: read.php?message=" . urlencode($message) . "&type=success");
                exit;
            } else {
                $message = "Error updating biodata: " . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            $message = "Error preparing update statement: " . $conn->error;
        }
    }

    // Re-fetch the updated data to display on the form in case of an error
    $sql_select_after_post = "SELECT * FROM biodata WHERE id = ?";
    if ($stmt_select_after_post = $conn->prepare($sql_select_after_post)) {
        $stmt_select_after_post->bind_param("i", $id);
        if ($stmt_select_after_post->execute()) {
            $result_after_post = $stmt_select_after_post->get_result();
            if ($result_after_post->num_rows == 1) {
                $biodata_entry = $result_after_post->fetch_assoc();
            }
        }
        $stmt_select_after_post->close();
    }
} else {
    $message = "Invalid request.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-top: 5px;
        }
        .radio-group label,
        .checkbox-group label {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            font-weight: normal;
        }
        .radio-group input[type="radio"],
        .checkbox-group input[type="checkbox"] {
            margin-right: 5px;
            width: auto;
        }
        .submit-area {
            text-align: center;
            margin-top: 20px;
        }
        input[type="submit"],
        input[type="reset"] {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }
        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .photo-container {
            margin-bottom: 15px;
            text-align: center;
        }
        .photo-container img {
            max-width: 200px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Biodata</h1>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : 'success'; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (!empty($biodata_entry)): ?>
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($biodata_entry['id']); ?>">
                <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($biodata_entry['photo_path'] ?? ''); ?>">
                
                <h2>Basic Information</h2>
                <div class="form-group">
                    <label>Current Photo:</label>
                    <?php if (!empty($biodata_entry['photo_path']) && file_exists($biodata_entry['photo_path'])): ?>
                        <div class="photo-container">
                            <img src="<?php echo htmlspecialchars($biodata_entry['photo_path']); ?>" alt="Current Profile Photo">
                            <p>To change, upload a new photo below.</p>
                        </div>
                    <?php else: ?>
                        <p>No photo uploaded yet.</p>
                    <?php endif; ?>
                    <label for="photo">Upload New Photo (optional):</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($biodata_entry['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($biodata_entry['date_of_birth'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="place_of_birth">Place of Birth:</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" value="<?php echo htmlspecialchars($biodata_entry['place_of_birth'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($biodata_entry['age'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male" <?php echo (isset($biodata_entry['gender']) && $biodata_entry['gender'] == 'Male') ? 'checked' : ''; ?> required>Male</label>
                        <label><input type="radio" name="gender" value="Female" <?php echo (isset($biodata_entry['gender']) && $biodata_entry['gender'] == 'Female') ? 'checked' : ''; ?>>Female</label>
                        <label><input type="radio" name="gender" value="Other" <?php echo (isset($biodata_entry['gender']) && $biodata_entry['gender'] == 'Other') ? 'checked' : ''; ?>>Other</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="height">Height:</label>
                    <input type="text" id="height" name="height" value="<?php echo htmlspecialchars($biodata_entry['height'] ?? ''); ?>" placeholder="e.g., 5' 10&quot; or 178 cm">
                </div>

                <div class="form-group">
                    <label for="marital_status">Marital Status:</label>
                    <select id="marital_status" name="marital_status">
                        <option value="Single" <?php echo (isset($biodata_entry['marital_status']) && $biodata_entry['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Divorced" <?php echo (isset($biodata_entry['marital_status']) && $biodata_entry['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                        <option value="Widowed" <?php echo (isset($biodata_entry['marital_status']) && $biodata_entry['marital_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="religion">Religion:</label>
                    <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($biodata_entry['religion'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="nationality">Nationality:</label>
                    <input type="text" id="nationality" name="nationality" value="<?php echo htmlspecialchars($biodata_entry['nationality'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="blood_group">Blood Group:</label>
                    <input type="text" id="blood_group" name="blood_group" value="<?php echo htmlspecialchars($biodata_entry['blood_group'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($biodata_entry['contact_number'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($biodata_entry['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="permanent_address">Permanent Address:</label>
                    <textarea id="permanent_address" name="permanent_address"><?php echo htmlspecialchars($biodata_entry['permanent_address'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="present_address">Present Address:</label>
                    <textarea id="present_address" name="present_address"><?php echo htmlspecialchars($biodata_entry['present_address'] ?? ''); ?></textarea>
                </div>

                <h2>Family Details</h2>
                <div class="form-group">
                    <label for="father_name">Father's Name:</label>
                    <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($biodata_entry['father_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="father_occupation">Father's Occupation:</label>
                    <input type="text" id="father_occupation" name="father_occupation" value="<?php echo htmlspecialchars($biodata_entry['father_occupation'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="mother_name">Mother's Name:</label>
                    <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($biodata_entry['mother_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="mother_occupation">Mother's Occupation:</label>
                    <input type="text" id="mother_occupation" name="mother_occupation" value="<?php echo htmlspecialchars($biodata_entry['mother_occupation'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="siblings">Siblings:</label>
                    <textarea id="siblings" name="siblings" placeholder="e.g., 2 brothers, 1 sister"><?php echo htmlspecialchars($biodata_entry['siblings'] ?? ''); ?></textarea>
                </div>

                <h2>Education & Career</h2>
                <div class="form-group">
                    <label for="education_qualification">Highest Education Qualification:</label>
                    <input type="text" id="education_qualification" name="education_qualification" value="<?php echo htmlspecialchars($biodata_entry['education_qualification'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="education_institute">Institute:</label>
                    <input type="text" id="education_institute" name="education_institute" value="<?php echo htmlspecialchars($biodata_entry['education_institute'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="education_passing_year">Passing Year:</label>
                    <input type="number" id="education_passing_year" name="education_passing_year" value="<?php echo htmlspecialchars($biodata_entry['education_passing_year'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="current_education">Current Education (if any):</label>
                    <input type="text" id="current_education" name="current_education" value="<?php echo htmlspecialchars($biodata_entry['current_education'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="certifications">Certifications:</label>
                    <textarea id="certifications" name="certifications"><?php echo htmlspecialchars($biodata_entry['certifications'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="occupation">Occupation:</label>
                    <input type="text" id="occupation" name="occupation" value="<?php echo htmlspecialchars($biodata_entry['occupation'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="annual_income">Annual Income:</label>
                    <input type="text" id="annual_income" name="annual_income" value="<?php echo htmlspecialchars($biodata_entry['annual_income'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="career_plan">Career Plan:</label>
                    <textarea id="career_plan" name="career_plan"><?php echo htmlspecialchars($biodata_entry['career_plan'] ?? ''); ?></textarea>
                </div>

                <h2>Appearance & Lifestyle</h2>
                <div class="form-group">
                    <label for="complexion">Complexion:</label>
                    <input type="text" id="complexion" name="complexion" value="<?php echo htmlspecialchars($biodata_entry['complexion'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="body_type">Body Type:</label>
                    <input type="text" id="body_type" name="body_type" value="<?php echo htmlspecialchars($biodata_entry['body_type'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="diet">Diet:</label>
                    <input type="text" id="diet" name="diet" value="<?php echo htmlspecialchars($biodata_entry['diet'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Smoking:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="smoking" value="No" <?php echo (isset($biodata_entry['smoking']) && $biodata_entry['smoking'] == 'No') ? 'checked' : ''; ?>>No</label>
                        <label><input type="radio" name="smoking" value="Occasionally" <?php echo (isset($biodata_entry['smoking']) && $biodata_entry['smoking'] == 'Occasionally') ? 'checked' : ''; ?>>Occasionally</label>
                        <label><input type="radio" name="smoking" value="Yes" <?php echo (isset($biodata_entry['smoking']) && $biodata_entry['smoking'] == 'Yes') ? 'checked' : ''; ?>>Yes</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Drinking:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="drinking" value="No" <?php echo (isset($biodata_entry['drinking']) && $biodata_entry['drinking'] == 'No') ? 'checked' : ''; ?>>No</label>
                        <label><input type="radio" name="drinking" value="Occasionally" <?php echo (isset($biodata_entry['drinking']) && $biodata_entry['drinking'] == 'Occasionally') ? 'checked' : ''; ?>>Occasionally</label>
                        <label><input type="radio" name="drinking" value="Yes" <?php echo (isset($biodata_entry['drinking']) && $biodata_entry['drinking'] == 'Yes') ? 'checked' : ''; ?>>Yes</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="hobbies">Hobbies:</label>
                    <textarea id="hobbies" name="hobbies"><?php echo htmlspecialchars($biodata_entry['hobbies'] ?? ''); ?></textarea>
                </div>

                <h2>Partner Preferences</h2>
                <div class="form-group">
                    <label for="partner_age_range">Age Range:</label>
                    <input type="text" id="partner_age_range" name="partner_age_range" value="<?php echo htmlspecialchars($biodata_entry['partner_age_range'] ?? ''); ?>" placeholder="e.g., 25-30">
                </div>

                <div class="form-group">
                    <label for="partner_height">Height:</label>
                    <input type="text" id="partner_height" name="partner_height" value="<?php echo htmlspecialchars($biodata_entry['partner_height'] ?? ''); ?>" placeholder="e.g., 5' 5&quot; - 5' 8&quot;">
                </div>

                <div class="form-group">
                    <label for="partner_education">Education:</label>
                    <input type="text" id="partner_education" name="partner_education" value="<?php echo htmlspecialchars($biodata_entry['partner_education'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="partner_occupation">Occupation:</label>
                    <input type="text" id="partner_occupation" name="partner_occupation" value="<?php echo htmlspecialchars($biodata_entry['partner_occupation'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="partner_religion">Religion:</label>
                    <input type="text" id="partner_religion" name="partner_religion" value="<?php echo htmlspecialchars($biodata_entry['partner_religion'] ?? ''); ?>">
                </div>

                <h2>Additional Information</h2>
                <div class="form-group">
                    <label for="about_me">About Me:</label>
                    <textarea id="about_me" name="about_me"><?php echo htmlspecialchars($biodata_entry['about_me'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="future_plans">Future Plans:</label>
                    <textarea id="future_plans" name="future_plans"><?php echo htmlspecialchars($biodata_entry['future_plans'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="health_issues">Health Issues (if any):</label>
                    <input type="text" id="health_issues" name="health_issues" value="<?php echo htmlspecialchars($biodata_entry['health_issues'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="languages">Languages Known:</label>
                    <textarea id="languages" name="languages"><?php echo htmlspecialchars($biodata_entry['languages'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="preferred_location">Preferred Location for Settling:</label>
                    <textarea id="preferred_location" name="preferred_location"><?php echo htmlspecialchars($biodata_entry['preferred_location'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="social_media">Social Media:</label>
                    <textarea id="social_media" name="social_media"><?php echo htmlspecialchars($biodata_entry['social_media'] ?? ''); ?></textarea>
                </div>

                <div class="submit-area">
                    <input type="submit" value="Update Biodata">
                    <a href="read.php" class="back-link" style="text-decoration: none; padding: 12px 20px; background-color: #6c757d; color: white; border-radius: 4px; display: inline-block;">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p>No biodata entry found to edit. Please <a href="read.php">go back</a> and select an entry to edit.</p>
        <?php endif; ?>
    </div>
</body>
</html>
