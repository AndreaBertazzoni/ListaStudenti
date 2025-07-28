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
    1 => ["name" => "Andrea", "surname" => "Rossi", "participations" => []],
    2 => ["name" => "Federico", "surname" => "Verdi", "participations" => []],
    3 => ["name" => "Francesco", "surname" => "Bianchi", "participations" => []],
    4 => ["name" => "Giacomo", "surname" => "Leopardi", "participations" => []],
];

// Get total number of students
$totalStudentCount = count($studentAttendanceRecord);

// Validate if there are any students, otherwise the program will display an error message and stop running
if ($totalStudentCount <= 0) {
    die("Errore: la lista studenti risulta vuota.");
}

// Define the number of days to track attendance (7 days by default)
$totalDays = 7;

// Validate if the day's number is positive, otherwise the program will display an error message and stop running
if ($totalDays <= 0) {
    die("Errore: il numero di giorni deve essere positivo.");
}

// Initialize daily attendance counter array with zeros for each day
$dailyAttendanceCount = array_fill_keys(range(1, $totalDays), 0);

// Generate random attendance data for each student on each day
foreach ($dailyAttendanceCount as $currentDay => &$c) {
    foreach ($studentAttendanceRecord as $studentId => $attendanceDays) {
        // Generate random boolean (0 or 1) to determine attendance
        $isPresent = random_int(0, 1);

        if ($isPresent) {
            // Add current day to student's attendance record
            $studentAttendanceRecord[$studentId]['participations'][] = $currentDay;
            // Increment daily attendance counter
            $dailyAttendanceCount[$currentDay]++;
        }
    }
}

// Display individual student attendance information
foreach ($studentAttendanceRecord as $studentId => $attendanceDays) {
    $student = $studentAttendanceRecord[$studentId];
    echo "{$student['name']} {$student['surname']}:<br>";

    // Show days when student was present (only if there are any)
    if (!$participations = $attendanceDays['participations']) {
        echo "&emsp;Presente nei giorni: " . implode(", ", $participations) . "<br>";
    }

    // Display total number of presences with correct Italian grammar
    echo "&emsp;" . count($participations) . " " . pluralize($participations, "presenza", "presenze") . "<br>";

    // Check if student was present every day
    echo "&emsp;Presente tutti i giorni: ";
    if (count($participations) == $totalDays) {
        echo "SI";
    } else {
        echo "NO";
    }
    echo "<br><br>";
}

// Print separator line
echo "<hr>";

// Display daily attendance statistics
foreach ($dailyAttendanceCount as $dayNumber => $presentCount) {
    $absentCount = $totalStudentCount - $presentCount;
    $attendancePercentage = calculatePercentage($presentCount, $totalStudentCount);

    // Display day statistics with proper Italian grammar for singular/plural
    echo "Giorno $dayNumber: $presentCount " . pluralize($presentCount, "presente", "presenti") .
        " e {$absentCount} " . pluralize($absentCount, "assente", "assenti");
    echo " => " . $attendancePercentage . "<br>";
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

echo pluralize(count($daysWithMaxAttendance), "Giorno", "Giorni") . " con più presenti: " . implode(", ", $daysWithMaxAttendance) . "<br><br>";

// Calculate and display total presence statistics
$totalPresenceCount = calculateTotalPresence($dailyAttendanceCount);
echo "Presenze totali: $totalPresenceCount <br><br>";

// Calculate overall attendance percentage and categorize it
$maxPossiblePresences = $totalDays * $totalStudentCount;
$overallAttendancePercentageNumber = calculatePercentage($totalPresenceCount, $maxPossiblePresences, 1, 'int');
$overallAttendancePercentage = calculatePercentage($totalPresenceCount, $maxPossiblePresences);
$attendanceCategory = null;

// Define attendance quality categories with conditions
$attendanceCategories = [
    ["minPercentage" => 0, "maxPercentage" => 25, "message" => "Scarsa"],
    ["minPercentage" => 25, "maxPercentage" => 50, "message" => "Moderata"],
    ["minPercentage" => 50, "maxPercentage" => 75, "message" => "Buona"],
    ["minPercentage" => 75, "maxPercentage" => 100, "message" => "Ottima"],
];

// Determine attendance condition based on percentage
foreach ($attendanceCategories as $condition) {
    $a = (float)$overallAttendancePercentageNumber;
    if ($overallAttendancePercentageNumber >= $condition["minPercentage"] && $overallAttendancePercentageNumber < $condition["maxPercentage"]) {
        $attendanceCategory = $condition["message"];
        break;
    }
}

echo "Media presenze: " . $overallAttendancePercentage . " ($attendanceCategory)<br><br>";

// Find and display students with highest attendance

$totParticipationsForStudent = array_map(function ($item) {
    return count($item['participations']);
}, $studentAttendanceRecord);
$maxStudentAttendance = max($totParticipationsForStudent);
$topAttendingStudents = [];

foreach ($studentAttendanceRecord as $studentId => $attendanceDays) {
    if (count($attendanceDays['participations']) === $maxStudentAttendance) {
        $topAttendingStudents[] = $studentId;
    }
}

$studentsNames = array_map(function ($sId) use ($studentAttendanceRecord) {
    return $studentAttendanceRecord[$sId]['name'] . ' ' . $studentAttendanceRecord[$sId]['surname'];
}, $topAttendingStudents);

// Display top attending students with proper grammar
echo pluralize(count($topAttendingStudents), "Studente", "Studenti") . " con più presenze: " . implode(", ", $studentsNames) . "<br><br>";

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
function calculatePercentage(int $partValue, int $totalValue, int $round = 1, $format = 'string'): float | string
{
    if (!$totalValue || $round < 1) {
        throw new Exception("Divisione con numero 0 non consentita.");
    }
    $result = number_format($partValue / $totalValue * 100, $round);

    return $format === 'string' ? formatPercentage($result) : $result;
}

/**
 * Format the percentage with one decimal number and the % sign at the end
 * 
 * @param float $value the number we want to format
 */
function formatPercentage($value): string
{
    return str_replace(".", ",", $value) . "%";
}

/**
 * Format the output based on singular or plural (in italian)
 */
function pluralize($count, $singular, $plural): string
{
    return $count === 1 ? $singular : $plural;
}
