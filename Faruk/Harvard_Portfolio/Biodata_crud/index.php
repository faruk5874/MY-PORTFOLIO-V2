<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Biodata Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top to prevent form overflowing vertically */
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* Add some padding to prevent content from touching edges */
            box-sizing: border-box;
            font-family: sans-serif;
        }
        .container {
            text-align: center;
            padding: 20px;
            border: 1px solid black;
            width: 800px; /* Adjust width to accommodate more fields */
            max-width: 90%; /* Responsive width */
            margin-top: 20px; /* Add top margin */
        }
        form {
            text-align: left; /* Align form labels and inputs to the left within the container */
            display: inline-block; /* Make form a block element that can be centered */
        }
        .form-group {
            margin-bottom: 10px;
            display: flex; /* Use flexbox for label-input alignment */
            align-items: center;
        }
        label {
            display: inline-block;
            width: 180px; /* Adjust label width for better alignment */
            text-align: right;
            margin-right: 15px;
            flex-shrink: 0; /* Prevent label from shrinking */
        }
        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="number"],
        input[type="tel"],
        input[type="file"], /* Added style for file input */
        select,
        textarea {
            padding: 5px;
            width: 300px; /* Adjust input width */
            border: 1px solid black;
            box-sizing: border-box; /* Include padding and border in element's total width/height */
            flex-grow: 1; /* Allow input to grow */
        }
        textarea {
            resize: vertical; /* Allow vertical resizing for textareas */
            min-height: 60px;
        }
        input[type="submit"],
        input[type="reset"] {
            padding: 8px 15px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px; /* Space between buttons */
        }
        h3 {
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid black;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Marriage Biodata Form</h1>
        <form id="biodataForm" method="POST" action="create.php" enctype="multipart/form-data">
            <h3>Personal Information</h3>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob">
            </div>
            <div class="form-group">
                <label for="pob">Place of Birth:</label>
                <input type="text" id="pob" name="pob">
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="1">
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="height">Height:</label>
                <input type="text" id="height" name="height" placeholder="e.g., 5'10&quot; or 178 cm">
            </div>
            <div class="form-group">
                <label for="marital_status">Marital Status:</label>
                <select id="marital_status" name="marital_status">
                    <option value="">Select</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="religion">Religion:</label>
                <input type="text" id="religion" name="religion">
            </div>
            <div class="form-group">
                <label for="nationality">Nationality:</label>
                <input type="text" id="nationality" name="nationality">
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group:</label>
                <select id="blood_group" name="blood_group">
                    <option value="">Select</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="permanent_address">Permanent Address:</label>
                <textarea id="permanent_address" name="permanent_address"></textarea>
            </div>
            <div class="form-group">
                <label for="present_address">Present Address:</label>
                <textarea id="present_address" name="present_address"></textarea>
            </div>

            <h3>Family Details</h3>
            <div class="form-group">
                <label for="father_name">Father's Name:</label>
                <input type="text" id="father_name" name="father_name">
            </div>
            <div class="form-group">
                <label for="father_occupation">Father's Occupation:</label>
                <input type="text" id="father_occupation" name="father_occupation">
            </div>
            <div class="form-group">
                <label for="mother_name">Mother's Name:</label>
                <input type="text" id="mother_name" name="mother_name">
            </div>
            <div class="form-group">
                <label for="mother_occupation">Mother's Occupation:</label>
                <input type="text" id="mother_occupation" name="mother_occupation">
            </div>
            <div class="form-group">
                <label for="siblings">Number of Siblings:</label>
                <select id="siblings" name="siblings">
                    <option value="">Select number of siblings</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5 or more">5 or more</option>
                </select>
            </div>

            <h3>Education & Profession</h3>
            <div class="form-group">
                <label for="highest_qualification">Highest Qualification:</label>
                <input type="text" id="highest_qualification" name="highest_qualification">
            </div>
            <div class="form-group">
                <label for="university_college_name">University/College Name:</label>
                <input type="text" id="university_college_name" name="university_college_name">
            </div>
            <div class="form-group">
                <label for="additional_certifications">Additional Certifications:</label>
                <textarea id="additional_certifications" name="additional_certifications"></textarea>
            </div>
            <div class="form-group">
                <label for="current_occupation">Current Occupation:</label>
                <input type="text" id="current_occupation" name="current_occupation">
            </div>
            <div class="form-group">
                <label for="annual_income">Annual Income:</label>
                <select id="annual_income" name="annual_income">
                    <option value="">Select your annual income</option>
                    <option value="Currently Unemployed">Currently Unemployed</option>
                    <option value="0 - 10,000 Tk">0 - 10,000 Tk</option>
                    <option value="10,001 - 20,000 Tk">10,001 - 20,000 Tk</option>
                    <option value="20,001 - 30,000 Tk">20,001 - 30,000 Tk</option>
                    <option value="30,001 - 50,000 Tk">30,001 - 50,000 Tk</option>
                    <option value="50,001 - 70,000 Tk">50,001 - 70,000 Tk</option>
                    <option value="70,001 - 100,000 Tk">70,001 - 100,000 Tk</option>
                    <option value="100,001 - 150,000 Tk">100,001 - 150,000 Tk</option>
                    <option value="150,001 - 200,000 Tk">150,001 - 200,000 Tk</option>
                    <option value="Above 200,000 Tk">Above 200,000 Tk</option>
                </select>
            </div>
            <div class="form-group">
                <label for="future_career_plan">Future Career Plan:</label>
                <textarea id="future_career_plan" name="future_career_plan"></textarea>
            </div>

            <h3>Appearance & Lifestyle</h3>
            <div class="form-group">
                <label for="complexion">Complexion:</label>
                <select id="complexion" name="complexion">
                    <option value="">Select your complexion</option>
                    <option value="Very Fair">Very Fair</option>
                    <option value="Fair">Fair</option>
                    <option value="Wheatish">Wheatish</option>
                    <option value="Medium">Medium</option>
                    <option value="Dusky">Dusky</option>
                    <option value="Dark">Dark</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="body_type">Body Type:</label>
                <select id="body_type" name="body_type">
                    <option value="">Select your body type</option>
                    <option value="Slim">Slim</option>
                    <option value="Athletic">Athletic</option>
                    <option value="Average">Average</option>
                    <option value="Fit">Fit</option>
                    <option value="Chubby">Chubby</option>
                    <option value="Heavy">Heavy</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="diet">Diet:</label>
                <select id="diet" name="diet">
                    <option value="">Select your diet preference</option>
                    <option value="Vegetarian">Vegetarian</option>
                    <option value="Non-Vegetarian">Non-Vegetarian</option>
                    <option value="Eggetarian (Vegetarian + Eggs)">Eggetarian (Vegetarian + Eggs)</option>
                    <option value="Vegan (No animal products)">Vegan (No animal products)</option>
                    <option value="Halal">Halal</option>
                    <option value="Kosher">Kosher</option>
                    <option value="Occasionally Non-Vegetarian">Occasionally Non-Vegetarian</option>
                    <option value="Pescatarian (Fish but no other meat)">Pescatarian (Fish but no other meat)</option>
                    <option value="Jain (Strict vegetarian, no root vegetables)">Jain (Strict vegetarian, no root vegetables)</option>
                    <option value="No Specific Preference">No Specific Preference</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="smoking">Smoking:</label>
                <select id="smoking" name="smoking">
                    <option value="">Select</option>
                    <option value="No">No</option>
                    <option value="Occasionally">Occasionally</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="drinking">Drinking:</label>
                <select id="drinking" name="drinking">
                    <option value="">Select</option>
                    <option value="No">No</option>
                    <option value="Occasionally">Occasionally</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hobbies_interests">Hobbies/Interests:</label>
                <textarea id="hobbies_interests" name="hobbies_interests"></textarea>
            </div>

            <h3>Partner Preferences</h3>
            <div class="form-group">
                <label for="partner_age_range">Age Range:</label>
                <input type="text" id="partner_age_range" name="partner_age_range" placeholder="e.g., 25-30">
            </div>
            <div class="form-group">
                <label for="partner_height">Height:</label>
                <input type="text" id="partner_height" name="partner_height" placeholder="e.g., 5'5&quot; - 5'9&quot;">
            </div>
            <div class="form-group">
                <label for="partner_education">Education:</label>
                <input type="text" id="partner_education" name="partner_education">
            </div>
            <div class="form-group">
                <label for="partner_occupation">Occupation:</label>
                <input type="text" id="partner_occupation" name="partner_occupation">
            </div>
            <div class="form-group">
                <label for="partner_religion">Religion:</label>
                <input type="text" id="partner_religion" name="partner_religion">
            </div>

            <h3>Additional Details</h3>
            <div class="form-group">
                <label for="about_me">About Me:</label>
                <textarea id="about_me" name="about_me"></textarea>
            </div>
            <div class="form-group">
                <label for="future_plans">Future Plans:</label>
                <textarea id="future_plans" name="future_plans"></textarea>
            </div>
            <div class="form-group">
                <label for="health_issues">Disabilities or Health Issues:</label>
                <select id="health_issues" name="health_issues">
                    <option value="">Select if applicable</option>
                    <option value="None">None</option>
                    <option value="Visual Impairment">Visual Impairment</option>
                    <option value="Hearing Impairment">Hearing Impairment</option>
                    <option value="Physical Disability">Physical Disability</option>
                    <option value="Speech Disorder">Speech Disorder</option>
                    <option value="Mental Health Condition">Mental Health Condition</option>
                    <option value="Chronic Illness (e.g., Diabetes, Asthma)">Chronic Illness (e.g., Diabetes, Asthma)</option>
                    <option value="Neurological Condition">Neurological Condition</option>
                    <option value="Other (please specify)">Other (please specify)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <textarea id="comments" name="comments"></textarea>
            </div>

            <div class="submit-area">
                <input type="submit" value="Submit Biodata">
                <input type="reset" value="Reset">
            </div>
        </form>
        <p><a href="read.php">Back to Biodata List</a></p>
    </div>
</body>
</html>