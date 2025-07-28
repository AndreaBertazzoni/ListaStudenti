<?php
/**
 * Student Attendance Tracker
 * 
 * This script simulates student attendance over a specified number of days,
 * generates random attendance data, and provides various statistics about
 * student presence and absence patterns.
 */

// Initialize student attendance tracking array with empty arrays for each student
$studentAttendanceRecord = [
    "Andrea Bertazzoni" => [],
    "Giada Altomare" => [],
    "Federico Carrassi" => [],
    "Antonino Beninato" => [],
];

// Get total number of students
$totalStudentCount = count($studentAttendanceRecord);

// Define the number of days to track attendance (7 days by default)
$totalDays = 7;

// Initialize daily attendance counter array with zeros for each day
$dailyAttendanceCount = array_fill_keys(range(1, $totalDays), 0);

// Generate random attendance data for each student on each day
for ($currentDay = 1; $currentDay <= $totalDays; $currentDay++) {
    foreach ($studentAttendanceRecord as $studentName => $attendanceDays) {
        // Generate random boolean (0 or 1) to determine attendance
        $isPresent = random_int(0, 1);
        
        if ($isPresent) {
            // Add current day to student's attendance record
            $studentAttendanceRecord[$studentName][] = $currentDay;
            // Increment daily attendance counter
            $dailyAttendanceCount[$currentDay]++;
        }
    }
}

// Display individual student attendance information
foreach ($studentAttendanceRecord as $studentName => $attendanceDays) {
    echo "$studentName:<br>";
    
    // Show days when student was present (only if there are any)
    if (!count($attendanceDays) == 0) {
        echo "&emsp;Presente nei giorni: " . implode(", ", $attendanceDays) . "<br>";
    }
    
    // Display total number of presences with correct Italian grammar
    echo "&emsp;" . count($attendanceDays) . " presenz" . ((count($attendanceDays) === 1 ? 'a' : 'e')) . "<br>";
    
    // Check if student was present every day
    echo "&emsp;Presente tutti i giorni: ";
    if (count($attendanceDays) == $totalDays) {
        echo "SI<br><br>";
    } else {
        echo "NO<br><br>";
    }
}

// Print separator line
echo str_repeat("-", 50) . "<br><br>";

// Display daily attendance statistics
foreach ($dailyAttendanceCount as $dayNumber => $presentCount) {
    $absentCount = $totalStudentCount - $presentCount;
    $attendancePercentage = number_format(calculatePercentage($presentCount, $totalStudentCount), 1);
    
    // Display day statistics with proper Italian grammar for singular/plural
    echo "Giorno $dayNumber: $presentCount present" . ($presentCount === 1 ? 'e' : 'i') . 
         " e {$absentCount} assent" . ($absentCount === 1 ? 'e' : 'i');
    echo " => " . str_replace(".", ",", $attendancePercentage) . "%<br>";
}

echo "<br>";

// Find and display days with highest attendance
$maxDailyPresence = max($dailyAttendanceCount);
$daysWithMaxAttendance = [];

foreach ($dailyAttendanceCount as $dayNumber => $presentCount) {
    if ($presentCount === $maxDailyPresence) {
        $daysWithMaxAttendance[] = $dayNumber;
    }
}

echo "Giorni con più presenti: " . implode(", ", $daysWithMaxAttendance) . "<br><br>";

// Calculate and display total presence statistics
$totalPresenceCount = calculateTotalPresence($dailyAttendanceCount);
echo "Presenze totali: $totalPresenceCount <br><br>";

// Calculate overall attendance percentage and categorize it
$maxPossiblePresences = $totalDays * $totalStudentCount;
$overallAttendancePercentage = number_format(calculatePercentage($totalPresenceCount, $maxPossiblePresences), 1);
$attendanceCategory = null;

// Define attendance quality categories with conditions
$attendanceCategories = [
    ["condition" => fn($percentage) => $percentage >= 0 && $percentage <= 24.99, "message" => "Scarsa"],
    ["condition" => fn($percentage) => $percentage >= 25 && $percentage <= 49.99, "message" => "Moderata"],
    ["condition" => fn($percentage) => $percentage >= 50 && $percentage <= 74.99, "message" => "Buona"],
    ["condition" => fn($percentage) => $percentage >= 75, "message" => "Ottima"],
];

// Determine attendance category based on percentage
foreach ($attendanceCategories as $category) {
    if ($category["condition"]($overallAttendancePercentage)) {
        $attendanceCategory = $category["message"];
    }
}

echo "Media presenze: " . str_replace(".", ",", $overallAttendancePercentage) . "% ($attendanceCategory)<br><br>";

// Find and display students with highest attendance
$maxStudentAttendance = count(max($studentAttendanceRecord));
$topAttendingStudents = [];

foreach ($studentAttendanceRecord as $studentName => $attendanceDays) {
    if (count($attendanceDays) === $maxStudentAttendance) {
        $topAttendingStudents[] = $studentName;
    }
}

// Display top attending students with proper grammar
echo "Student" . (count($topAttendingStudents) === 1 ? "e" : "i") . 
     " con più presenze: " . implode(", ", $topAttendingStudents) . "<br><br>";

/**
 * Calculate the total number of presences across all days
 * 
 * @param array $dailyAttendanceData Array containing daily attendance counts
 * @return int Total number of presences
 */
function calculateTotalPresence(array $dailyAttendanceData): int
{
    return array_sum($dailyAttendanceData);
}

/**
 * Calculate percentage of a part relative to the total
 * 
 * @param int $partValue The part value
 * @param int $totalValue The total value
 * @return float The calculated percentage
 */
function calculatePercentage(int $partValue, int $totalValue): float
{
    return ($partValue / $totalValue) * 100;
}