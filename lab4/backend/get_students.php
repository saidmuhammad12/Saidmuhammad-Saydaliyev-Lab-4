<?php
// Sends all saved students to the frontend as JSON
require "config.php";

$students = load_students();

foreach ($students as &$s) {
    $s["grade"] = grade($s["marks"]);
}
unset($s);

$average = count($students) ? round(array_sum(array_column($students, "marks")) / count($students), 1) : 0;

echo json_encode([
    "count" => count($students),
    "average" => $average,
    "students" => $students,
]);
