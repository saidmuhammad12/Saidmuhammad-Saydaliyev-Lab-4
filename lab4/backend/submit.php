<?php
// Receives the form data (POST), validates it and saves it
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "errors" => ["Only POST requests are allowed."]]);
    exit;
}

$name = clean($_POST["name"] ?? "");
$marks = filter_input(INPUT_POST, "marks", FILTER_VALIDATE_INT);

$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}

if ($marks === false || $marks === null || $marks < 0 || $marks > 100) {
    $errors[] = "Marks must be an integer between 0 and 100.";
}

if (!empty($errors)) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

$students = load_students();

$new_student = [
    "id" => uniqid(),
    "name" => $name,
    "marks" => $marks,
    "grade" => grade($marks)
];

$students[] = $new_student;
save_students($students);

echo json_encode(["success" => true, "student" => $new_student]);
$enrollment = clean($_POST["enrollment"] ?? "");
$email = clean($_POST["email"] ?? "");
$course = clean($_POST["course"] ?? "");
$gender = clean($_POST["gender"] ?? "");
$marks = $_POST["marks"] ?? "";

// ---- Validation ----
$errors = [];
if ($name === "") $errors[] = "Name is required.";
if ($enrollment === "") $errors[] = "Enrollment number is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
if (!in_array($course, ["B.Tech CSE", "BCA", "MCA", "B.Sc IT"])) $errors[] = "Please select a course.";
if (!in_array($gender, ["Male", "Female", "Other"])) $errors[] = "Please select gender.";
if (!is_numeric($marks) || $marks < 0 || $marks > 100) $errors[] = "Marks must be between 0 and 100.";

if ($errors) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// ---- Save ----
$students = load_students();
$students[] = [
    "enrollment" => $enrollment,
    "name" => $name,
    "email" => $email,
    "course" => $course,
    "gender" => $gender,
    "marks" => (int)$marks,
];
save_students($students);
echo json_encode(["success" => true, "message" => "Student $name saved successfully!"]);