<?php
session_start();

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
// Make sure you have a db.php file with your database connection logic
include 'db.php';

$message = "";
$message_type = "";

// Define the upload directory
$upload_dir = 'uploads/';

// Ensure the uploads directory exists and is writable
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Collect and sanitize all input fields ---
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

    // --- Server-side validation ---
    $errors = [];
    $required_fields = [
        'name' => $name, 'date of birth' => $date_of_birth, 'place of birth' => $place_of_birth, 'age' => $age, 'gender' => $gender, 'height' => $height, 'marital status' => $marital_status, 'religion' => $religion, 'nationality' => $nationality, 'blood group' => $blood_group, 'contact number' => $contact_number, 'email' => $email, 'permanent address' => $permanent_address, 'present address' => $present_address, 'father\'s name' => $father_name, 'father\'s occupation' => $father_occupation, 'mother\'s name' => $mother_name, 'mother\'s occupation' => $mother_occupation, 'siblings' => $siblings, 'highest education qualification' => $education_qualification, 'institute' => $education_institute, 'passing year' => $education_passing_year, 'current education' => $current_education, 'certifications' => $certifications, 'occupation' => $occupation, 'annual income' => $annual_income, 'career plan' => $career_plan, 'complexion' => $complexion, 'body type' => $body_type, 'diet' => $diet, 'smoking' => $smoking, 'drinking' => $drinking, 'hobbies' => $hobbies, 'partner age range' => $partner_age_range, 'partner height' => $partner_height, 'partner education' => $partner_education, 'partner occupation' => $partner_occupation, 'partner religion' => $partner_religion, 'about me' => $about_me, 'future plans' => $future_plans, 'health issues' => $health_issues, 'languages known' => $languages, 'preferred location' => $preferred_location
    ];

    foreach ($required_fields as $field_name => $value) {
        if (empty($value)) {
            $errors[] = ucfirst($field_name) . " is required.";
        }
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // --- Handle photo upload (accepts all file types) ---
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $unique_file_name = uniqid('photo_', true) . '_' . $file_name;
        $destination = $upload_dir . $unique_file_name;

        if (!move_uploaded_file($file_tmp_name, $destination)) {
            $errors[] = "Error uploading photo.";
        } else {
            $photo_path = $destination;
        }
    } else {
        $errors[] = "Photo is required.";
    }

    // --- Insert into database if no errors ---
    if (empty($errors)) {
        $sql_insert = "INSERT INTO biodata (
            photo_path, name, date_of_birth, place_of_birth, age, gender, height, marital_status, religion, nationality, 
            blood_group, contact_number, email, permanent_address, present_address, father_name, father_occupation, 
            mother_name, mother_occupation, siblings, education_qualification, education_institute, education_passing_year, 
            current_education, certifications, occupation, annual_income, career_plan, complexion, body_type, diet, smoking, 
            drinking, hobbies, partner_age_range, partner_height, partner_education, partner_occupation, partner_religion, 
            about_me, future_plans, health_issues, languages, preferred_location, social_media
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql_insert)) {
            $stmt->bind_param(
                "sssisssssssssssssssssisssssssssssssssssssssss",
                $photo_path, $name, $date_of_birth, $place_of_birth, $age, $gender, $height, $marital_status, $religion, $nationality,
                $blood_group, $contact_number, $email, $permanent_address, $present_address, $father_name, $father_occupation,
                $mother_name, $mother_occupation, $siblings, $education_qualification, $education_institute, $education_passing_year,
                $current_education, $certifications, $occupation, $annual_income, $career_plan, $complexion, $body_type, $diet, $smoking,
                $drinking, $hobbies, $partner_age_range, $partner_height, $partner_education, $partner_occupation, $partner_religion,
                $about_me, $future_plans, $health_issues, $languages, $preferred_location, $social_media
            );

            if ($stmt->execute()) {
                $message = "Biodata created successfully!";
                header("Location: read.php?message=" . urlencode($message) . "&type=success");
                exit;
            } else {
                $message = "Error: " . $stmt->error;
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "error";
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}

if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Biodata</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Merriweather', serif; background: #e9ebee; padding: 40px 20px; min-height: 100vh; color: #333; }
        .container { max-width: 800px; width: 100%; background-color: #fff; padding: 50px 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); margin: auto; }
        .container .title { font-size: 30px; font-weight: 700; position: relative; text-align: center; margin-bottom: 50px; color: #1c1e21; line-height: 1.4; }
        .container .title::before { content: ""; position: absolute; left: 50%; bottom: -15px; transform: translateX(-50%); height: 4px; width: 80px; border-radius: 2px; background: #007bff; }
        
        .form-section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            background: #eef2f7;
            padding: 12px 20px;
            border-radius: 6px;
            margin-top: 40px;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        .form-group { margin-bottom: 22px; width: 100%; }
        .form-group label { display: block; font-weight: 700; margin-bottom: 8px; color: #34495e; font-size: 16px; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="date"], .form-group input[type="tel"], .form-group input[type="number"], .form-group select, .form-group textarea { 
            height: 45px; 
            width: 100%; 
            outline: none; 
            font-size: 16px; 
            border-radius: 5px; 
            padding: 0 15px; 
            border: 1px solid #ccc; 
            transition: border-color 0.3s ease, box-shadow 0.3s ease; 
            font-family: 'Merriweather', serif;
            font-weight: 400;
            color: #333;
        }
        .form-group input[type="file"] { padding: 10px 15px; }
        .form-group textarea { height: 100px; padding-top: 15px; resize: vertical; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #007bff; box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25); }
        .radio-group { display: flex; gap: 20px; align-items: center; margin-top: 10px; flex-wrap: wrap;}
        .radio-group label { display: flex; align-items: center; cursor: pointer; font-weight: 400; font-size: 16px; color: #333; }
        .radio-group input[type="radio"] { margin-right: 8px; }
        .submit-area { text-align: center; margin-top: 40px; }
        .submit-area input[type="submit"], .submit-area .cancel-link { padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: 700; letter-spacing: 1px; transition: background-color 0.3s ease; text-decoration: none; display: inline-block; margin: 10px; font-family: 'Merriweather', serif; }
        .submit-area input[type="submit"] { background: #007bff; color: #fff; }
        .submit-area input[type="submit"]:hover { background: #0056b3; }
        .submit-area .cancel-link { background: #6c757d; color: #fff; }
        .submit-area .cancel-link:hover { background: #5a6268; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-size: 16px; }
        .message.success { background-color: #d4edda; color: #155724; }
        .message.error { background-color: #f8d7da; color: #721c24; }
        
        /* --- Validation Styles --- */
        .error-message { color: #dc3545; font-size: 14px; margin-top: 5px; display: none; height: 15px; font-weight: 400; }
        .form-group input.success, .form-group select.success, .form-group textarea.success { border-color: #28a745; }
        .form-group input.error, .form-group select.error, .form-group textarea.error { border-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Matrimonial <br> Bio-data Form</div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo htmlspecialchars($message_type); ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="create.php" method="post" enctype="multipart/form-data" id="biodataForm">
            
            <h2 class="form-section-title">Basic Information</h2>
            <div class="form-group">
                <label for="photo">Upload Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <div class="error-message" id="photo-error"></div>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                <div class="error-message" id="name-error"></div>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? ''); ?>">
                <div class="error-message" id="date_of_birth-error"></div>
            </div>
            <div class="form-group">
                <label for="place_of_birth">Place of Birth</label>
                <input type="text" id="place_of_birth" name="place_of_birth" placeholder="e.g., Dhaka" value="<?php echo htmlspecialchars($_POST['place_of_birth'] ?? ''); ?>">
                <div class="error-message" id="place_of_birth-error"></div>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
                <div class="error-message" id="age-error"></div>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'checked' : ''; ?>>Male</label>
                    <label><input type="radio" name="gender" value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'checked' : ''; ?>>Female</label>
                    <label><input type="radio" name="gender" value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'checked' : ''; ?>>Other</label>
                </div>
                <div class="error-message" id="gender-error"></div>
            </div>
            <div class="form-group">
                <label for="height">Height</label>
                <input type="text" id="height" name="height" placeholder="e.g., 5' 10&quot; or 178 cm" value="<?php echo htmlspecialchars($_POST['height'] ?? ''); ?>">
                <div class="error-message" id="height-error"></div>
            </div>
            <div class="form-group">
                <label for="marital_status">Marital Status</label>
                <select id="marital_status" name="marital_status">
                    <option value="" disabled <?php echo !isset($_POST['marital_status']) ? 'selected' : ''; ?>>Select status</option>
                    <option value="Single" <?php echo (isset($_POST['marital_status']) && $_POST['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                    <option value="Divorced" <?php echo (isset($_POST['marital_status']) && $_POST['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                    <option value="Widowed" <?php echo (isset($_POST['marital_status']) && $_POST['marital_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                </select>
                <div class="error-message" id="marital_status-error"></div>
            </div>
            <div class="form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion" placeholder="e.g., Islam" value="<?php echo htmlspecialchars($_POST['religion'] ?? ''); ?>">
                <div class="error-message" id="religion-error"></div>
            </div>
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" id="nationality" name="nationality" placeholder="e.g., Bangladeshi" value="<?php echo htmlspecialchars($_POST['nationality'] ?? ''); ?>">
                <div class="error-message" id="nationality-error"></div>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group" placeholder="e.g., O+" value="<?php echo htmlspecialchars($_POST['blood_group'] ?? ''); ?>">
                <div class="error-message" id="blood_group-error"></div>
            </div>

            <h2 class="form-section-title">Contact Information</h2>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="tel" id="contact_number" name="contact_number" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($_POST['contact_number'] ?? ''); ?>">
                <div class="error-message" id="contact_number-error"></div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <div class="error-message" id="email-error"></div>
            </div>
            <div class="form-group">
                <label for="permanent_address">Permanent Address</label>
                <textarea id="permanent_address" name="permanent_address" placeholder="Enter permanent address"><?php echo htmlspecialchars($_POST['permanent_address'] ?? ''); ?></textarea>
                <div class="error-message" id="permanent_address-error"></div>
            </div>
            <div class="form-group">
                <label for="present_address">Present Address</label>
                <textarea id="present_address" name="present_address" placeholder="Enter present address"><?php echo htmlspecialchars($_POST['present_address'] ?? ''); ?></textarea>
                <div class="error-message" id="present_address-error"></div>
            </div>

            <h2 class="form-section-title">Family Details</h2>
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" id="father_name" name="father_name" placeholder="Enter father's name" value="<?php echo htmlspecialchars($_POST['father_name'] ?? ''); ?>">
                <div class="error-message" id="father_name-error"></div>
            </div>
            <div class="form-group">
                <label for="father_occupation">Father's Occupation</label>
                <input type="text" id="father_occupation" name="father_occupation" placeholder="Enter father's occupation" value="<?php echo htmlspecialchars($_POST['father_occupation'] ?? ''); ?>">
                <div class="error-message" id="father_occupation-error"></div>
            </div>
            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" id="mother_name" name="mother_name" placeholder="Enter mother's name" value="<?php echo htmlspecialchars($_POST['mother_name'] ?? ''); ?>">
                <div class="error-message" id="mother_name-error"></div>
            </div>
            <div class="form-group">
                <label for="mother_occupation">Mother's Occupation</label>
                <input type="text" id="mother_occupation" name="mother_occupation" placeholder="Enter mother's occupation" value="<?php echo htmlspecialchars($_POST['mother_occupation'] ?? ''); ?>">
                <div class="error-message" id="mother_occupation-error"></div>
            </div>
            <div class="form-group">
                <label for="siblings">Siblings</label>
                <textarea id="siblings" name="siblings" placeholder="e.g., 1 brother, 2 sisters"><?php echo htmlspecialchars($_POST['siblings'] ?? ''); ?></textarea>
                <div class="error-message" id="siblings-error"></div>
            </div>

            <h2 class="form-section-title">Education & Career</h2>
            <div class="form-group">
                <label for="education_qualification">Highest Education</label>
                <input type="text" id="education_qualification" name="education_qualification" placeholder="e.g., B.Sc. in CSE" value="<?php echo htmlspecialchars($_POST['education_qualification'] ?? ''); ?>">
                <div class="error-message" id="education_qualification-error"></div>
            </div>
            <div class="form-group">
                <label for="education_institute">Institute</label>
                <input type="text" id="education_institute" name="education_institute" placeholder="e.g., University of Dhaka" value="<?php echo htmlspecialchars($_POST['education_institute'] ?? ''); ?>">
                <div class="error-message" id="education_institute-error"></div>
            </div>
            <div class="form-group">
                <label for="education_passing_year">Passing Year</label>
                <input type="number" id="education_passing_year" name="education_passing_year" placeholder="e.g., 2022" value="<?php echo htmlspecialchars($_POST['education_passing_year'] ?? ''); ?>">
                <div class="error-message" id="education_passing_year-error"></div>
            </div>
            <div class="form-group">
                <label for="current_education">Current Education (if any)</label>
                <input type="text" id="current_education" name="current_education" placeholder="e.g., M.Sc. running" value="<?php echo htmlspecialchars($_POST['current_education'] ?? ''); ?>">
                <div class="error-message" id="current_education-error"></div>
            </div>
            <div class="form-group">
                <label for="occupation">Occupation</label>
                <input type="text" id="occupation" name="occupation" placeholder="e.g., Software Engineer" value="<?php echo htmlspecialchars($_POST['occupation'] ?? ''); ?>">
                <div class="error-message" id="occupation-error"></div>
            </div>
            <div class="form-group">
                <label for="annual_income">Annual Income</label>
                <input type="text" id="annual_income" name="annual_income" placeholder="e.g., 1200000" value="<?php echo htmlspecialchars($_POST['annual_income'] ?? ''); ?>">
                <div class="error-message" id="annual_income-error"></div>
            </div>
            <div class="form-group">
                <label for="certifications">Certifications</label>
                <textarea id="certifications" name="certifications" placeholder="List any professional certifications"><?php echo htmlspecialchars($_POST['certifications'] ?? ''); ?></textarea>
                <div class="error-message" id="certifications-error"></div>
            </div>
            <div class="form-group">
                <label for="career_plan">Career Plan</label>
                <textarea id="career_plan" name="career_plan" placeholder="Describe your career goals"><?php echo htmlspecialchars($_POST['career_plan'] ?? ''); ?></textarea>
                <div class="error-message" id="career_plan-error"></div>
            </div>

            <h2 class="form-section-title">Appearance & Lifestyle</h2>
            <div class="form-group">
                <label for="complexion">Complexion</label>
                <input type="text" id="complexion" name="complexion" placeholder="e.g., Fair, Brown" value="<?php echo htmlspecialchars($_POST['complexion'] ?? ''); ?>">
                <div class="error-message" id="complexion-error"></div>
            </div>
            <div class="form-group">
                <label for="body_type">Body Type</label>
                <input type="text" id="body_type" name="body_type" placeholder="e.g., Slim, Average, Athletic" value="<?php echo htmlspecialchars($_POST['body_type'] ?? ''); ?>">
                <div class="error-message" id="body_type-error"></div>
            </div>
            <div class="form-group">
                <label for="diet">Diet</label>
                <input type="text" id="diet" name="diet" placeholder="e.g., Non-vegetarian, Vegetarian" value="<?php echo htmlspecialchars($_POST['diet'] ?? ''); ?>">
                <div class="error-message" id="diet-error"></div>
            </div>
            <div class="form-group">
                <label>Smoking</label>
                <div class="radio-group">
                    <label><input type="radio" name="smoking" value="No" <?php echo (isset($_POST['smoking']) && $_POST['smoking'] == 'No') ? 'checked' : ''; ?>>No</label>
                    <label><input type="radio" name="smoking" value="Occasionally" <?php echo (isset($_POST['smoking']) && $_POST['smoking'] == 'Occasionally') ? 'checked' : ''; ?>>Occasionally</label>
                    <label><input type="radio" name="smoking" value="Yes" <?php echo (isset($_POST['smoking']) && $_POST['smoking'] == 'Yes') ? 'checked' : ''; ?>>Yes</label>
                </div>
                <div class="error-message" id="smoking-error"></div>
            </div>
            <div class="form-group">
                <label>Drinking</label>
                <div class="radio-group">
                    <label><input type="radio" name="drinking" value="No" <?php echo (isset($_POST['drinking']) && $_POST['drinking'] == 'No') ? 'checked' : ''; ?>>No</label>
                    <label><input type="radio" name="drinking" value="Occasionally" <?php echo (isset($_POST['drinking']) && $_POST['drinking'] == 'Occasionally') ? 'checked' : ''; ?>>Occasionally</label>
                    <label><input type="radio" name="drinking" value="Yes" <?php echo (isset($_POST['drinking']) && $_POST['drinking'] == 'Yes') ? 'checked' : ''; ?>>Yes</label>
                </div>
                <div class="error-message" id="drinking-error"></div>
            </div>
            <div class="form-group">
                <label for="hobbies">Hobbies</label>
                <textarea id="hobbies" name="hobbies" placeholder="e.g., Reading, Traveling, Coding"><?php echo htmlspecialchars($_POST['hobbies'] ?? ''); ?></textarea>
                <div class="error-message" id="hobbies-error"></div>
            </div>

            <h2 class="form-section-title">Partner Preferences</h2>
            <div class="form-group">
                <label for="partner_age_range">Age Range</label>
                <input type="text" id="partner_age_range" name="partner_age_range" placeholder="e.g., 25-30" value="<?php echo htmlspecialchars($_POST['partner_age_range'] ?? ''); ?>">
                <div class="error-message" id="partner_age_range-error"></div>
            </div>
            <div class="form-group">
                <label for="partner_height">Height</label>
                <input type="text" id="partner_height" name="partner_height" placeholder="e.g., 5' 4&quot; - 5' 8&quot;" value="<?php echo htmlspecialchars($_POST['partner_height'] ?? ''); ?>">
                <div class="error-message" id="partner_height-error"></div>
            </div>
            <div class="form-group">
                <label for="partner_education">Education</label>
                <input type="text" id="partner_education" name="partner_education" placeholder="e.g., Graduate" value="<?php echo htmlspecialchars($_POST['partner_education'] ?? ''); ?>">
                <div class="error-message" id="partner_education-error"></div>
            </div>
            <div class="form-group">
                <label for="partner_occupation">Occupation</label>
                <input type="text" id="partner_occupation" name="partner_occupation" placeholder="e.g., Doctor, Engineer" value="<?php echo htmlspecialchars($_POST['partner_occupation'] ?? ''); ?>">
                <div class="error-message" id="partner_occupation-error"></div>
            </div>
            <div class="form-group">
                <label for="partner_religion">Religion</label>
                <input type="text" id="partner_religion" name="partner_religion" placeholder="e.g., Islam" value="<?php echo htmlspecialchars($_POST['partner_religion'] ?? ''); ?>">
                <div class="error-message" id="partner_religion-error"></div>
            </div>

            <h2 class="form-section-title">Additional Information</h2>
            <div class="form-group">
                <label for="about_me">About Me</label>
                <textarea id="about_me" name="about_me" placeholder="Write a brief description about yourself"><?php echo htmlspecialchars($_POST['about_me'] ?? ''); ?></textarea>
                <div class="error-message" id="about_me-error"></div>
            </div>
            <div class="form-group">
                <label for="future_plans">Future Plans</label>
                <textarea id="future_plans" name="future_plans" placeholder="Describe your future plans regarding career and family"><?php echo htmlspecialchars($_POST['future_plans'] ?? ''); ?></textarea>
                <div class="error-message" id="future_plans-error"></div>
            </div>
            <div class="form-group">
                <label for="health_issues">Health Issues (if any)</label>
                <input type="text" id="health_issues" name="health_issues" placeholder="e.g., None" value="<?php echo htmlspecialchars($_POST['health_issues'] ?? ''); ?>">
                <div class="error-message" id="health_issues-error"></div>
            </div>
            <div class="form-group">
                <label for="languages">Languages Known</label>
                <input type="text" id="languages" name="languages" placeholder="e.g., Bangla, English" value="<?php echo htmlspecialchars($_POST['languages'] ?? ''); ?>">
                <div class="error-message" id="languages-error"></div>
            </div>
            <div class="form-group">
                <label for="preferred_location">Preferred Location</label>
                <input type="text" id="preferred_location" name="preferred_location" placeholder="e.g., Dhaka" value="<?php echo htmlspecialchars($_POST['preferred_location'] ?? ''); ?>">
                <div class="error-message" id="preferred_location-error"></div>
            </div>
            <div class="form-group">
                <label for="social_media">Social Media (Optional)</label>
                <input type="text" id="social_media" name="social_media" placeholder="e.g., Link to profile" value="<?php echo htmlspecialchars($_POST['social_media'] ?? ''); ?>">
                <div class="error-message" id="social_media-error"></div>
            </div>

            <div class="submit-area">
                <input type="submit" value="Create Biodata">
                <a href="read.php" class="cancel-link">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('biodataForm');
            
            // --- Validation Rules ---
            const validationRules = {
                // Category 1: Alphabet and spaces only
                'name': { regex: /^[a-zA-Z\s.]+$/, message: 'Only alphabets and spaces are allowed.' },
                'nationality': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'religion': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'place_of_birth': { regex: /^[a-zA-Z\s,]+$/, message: 'Only alphabets, spaces, and commas are allowed.' },
                'father_name': { regex: /^[a-zA-Z\s.]+$/, message: 'Only alphabets and spaces are allowed.' },
                'father_occupation': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'mother_name': { regex: /^[a-zA-Z\s.]+$/, message: 'Only alphabets and spaces are allowed.' },
                'mother_occupation': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'education_institute': { regex: /^[a-zA-Z\s,.]+$/, message: 'Only alphabets, spaces, and commas are allowed.' },
                'occupation': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'certifications': { regex: /^[a-zA-Z\s,.-]+$/, message: 'Invalid characters.' },
                'career_plan': { regex: /^[a-zA-Z0-9\s,.-]+$/, message: 'Invalid characters.' },
                'complexion': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'body_type': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'diet': { regex: /^[a-zA-Z\s-]+$/, message: 'Only alphabets, spaces, and hyphens are allowed.' },
                'hobbies': { regex: /^[a-zA-Z0-9\s,]+$/, message: 'Invalid characters.' },
                'partner_education': { regex: /^[a-zA-Z\s,.]+$/, message: 'Invalid characters.' },
                'partner_occupation': { regex: /^[a-zA-Z\s,]+$/, message: 'Invalid characters.' },
                'partner_religion': { regex: /^[a-zA-Z\s]+$/, message: 'Only alphabets and spaces are allowed.' },
                'about_me': { regex: /^[a-zA-Z0-9\s.,'!?()-]+$/, message: 'Invalid characters.' },
                'future_plans': { regex: /^[a-zA-Z0-9\s.,'!?()-]+$/, message: 'Invalid characters.' },
                'health_issues': { regex: /^[a-zA-Z0-9\s,.-]+$/, message: 'Invalid characters.' },
                'languages': { regex: /^[a-zA-Z\s,]+$/, message: 'Only alphabets, spaces, and commas are allowed.' },
                'preferred_location': { regex: /^[a-zA-Z\s,]+$/, message: 'Only alphabets, spaces, and commas are allowed.' },
                'social_media': { regex: /^[a-zA-Z0-9\s.,'!?()/:_-]+$/, message: 'Invalid characters.', optional: true },

                // Category 2: Alphabet, symbols, and numbers
                'height': { regex: /^[a-zA-Z0-9\s'"-.]+$/, message: 'Invalid format. Use formats like 5\'10" or 178cm.' },
                'blood_group': { regex: /^(A|B|AB|O)[+-]$/i, message: 'Invalid blood group. Use A+, B-, etc.' },
                'permanent_address': { regex: /^[a-zA-Z0-9\s.,#-/]+$/, message: 'Invalid characters in address.' },
                'present_address': { regex: /^[a-zA-Z0-9\s.,#-/]+$/, message: 'Invalid characters in address.' },
                'siblings': { regex: /^[a-zA-Z0-9\s,]+$/, message: 'Invalid characters.' },
                'partner_age_range': { regex: /^\d{2}-\d{2}$/, message: 'Use format XX-XX (e.g., 25-30).' },
                'partner_height': { regex: /^[a-zA-Z0-9\s'"-.]+$/, message: 'Invalid format. Use formats like 5\'5"-5\'8".' },

                // Category 3: Numbers only
                'contact_number': { regex: /^\+?\d{10,15}$/, message: 'Enter a valid phone number (10-15 digits).' },
                'annual_income': { regex: /^\d+$/, message: 'Please enter numbers only.' },
                'age': { regex: /^\d{1,3}$/, message: 'Please enter a valid age.' },
                'education_passing_year': { regex: /^\d{4}$/, message: 'Please enter a valid 4-digit year.' },
                
                // Other validations
                'email': { regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Please enter a valid email address.' },
            };
            
            // --- Attach event listeners for real-time validation ---
            form.querySelectorAll('input, select, textarea').forEach(element => {
                element.addEventListener('input', () => validateField(element));
                element.addEventListener('change', () => validateField(element));
            });

            // --- Main submit event ---
            form.addEventListener('submit', function (e) {
                if (!runFullFormValidation()) {
                    e.preventDefault(); 
                }
            });

            // --- Helper Functions for setting styles ---
            const setError = (element, message) => {
                const inputControl = element.closest('.form-group');
                const errorDisplay = inputControl.querySelector('.error-message');
                errorDisplay.innerText = message;
                element.classList.add('error');
                element.classList.remove('success');
                errorDisplay.style.display = 'block';
            }

            const setSuccess = (element) => {
                const inputControl = element.closest('.form-group');
                const errorDisplay = inputControl.querySelector('.error-message');
                errorDisplay.innerText = '';
                element.classList.add('success');
                element.classList.remove('error');
                errorDisplay.style.display = 'none';
            };

            // --- Real-time validation for a single field ---
            const validateField = (element) => {
                const id = element.id;
                const value = element.value.trim();
                const rule = validationRules[id];
                let isValid = true;
                
                // Handle optional fields
                if (rule && rule.optional && value === '') {
                    setSuccess(element);
                    return true;
                }

                // Required check for all non-optional fields
                if (value === '') {
                    setError(element, 'This field is required.');
                    return false;
                }
                
                // Regex validation if a rule exists
                if (rule && !rule.regex.test(value)) {
                    setError(element, rule.message);
                    isValid = false;
                } else if (id === 'photo' && element.files.length === 0) {
                     setError(element, 'Photo is required.');
                     isValid = false;
                } else if (id === 'date_of_birth' && value === '') {
                    setError(element, 'Date of Birth is required.');
                    isValid = false;
                } else if (id === 'marital_status' && value === '') {
                    setError(element, 'Please select a status.');
                    isValid = false;
                }
                else {
                    setSuccess(element);
                }

                return isValid;
            };

            // --- Function to run validation on all fields (for submit button) ---
            const runFullFormValidation = () => {
                let isFormValid = true;
                const fieldsToValidate = form.querySelectorAll('input:not([type="submit"]), select, textarea');
                
                fieldsToValidate.forEach(element => {
                    if (!validateField(element)) {
                        isFormValid = false;
                    }
                });
                
                // Final check for radio button groups
                ['gender', 'smoking', 'drinking'].forEach(groupName => {
                    const errorContainer = document.getElementById(`${groupName}-error`);
                    if(!document.querySelector(`input[name="${groupName}"]:checked`)) {
                        errorContainer.innerText = `Please select an option.`;
                        errorContainer.style.display = 'block';
                        isFormValid = false;
                    } else {
                        errorContainer.style.display = 'none';
                    }
                });

                if (!isFormValid) {
                    const firstError = document.querySelector('.error, .error-message[style*="display: block"]');
                    if (firstError) {
                        firstError.closest('.form-group').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                return isFormValid;
            };
        });
    </script>

</body>
</html>