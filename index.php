<?php

$students_attendance = [
    "Andrea Bertazzoni" => [],
    "Giada Altomare" => [],
    "Federico Carrassi" => [],
    "Antonino Beninato" => [],
];

$number_of_students = count($students_attendance);
$days = 7;

$daily_attendance = array_fill_keys(range(1, $days), 0);

for ($day = 1; $day <= $days; $day++) {
    foreach ($students_attendance as $student => $attendance) {
        $random_bool = random_int(0, 1);
        if ($random_bool) {
            $students_attendance[$student][] = $day;
            $daily_attendance[$day]++;
        }
    }
}

foreach ($students_attendance as $student => $attendance) {
    echo "$student:<br>";
    if (!count($attendance) == 0) {
        echo "&emsp;Presente nei giorni: " . implode(", ", $attendance) . "<br>";
    }
    echo "&emsp;" . count($attendance) . " presenz" . ((count($attendance) === 1 ? 'a' : 'e')) . "<br>";
    echo "&emsp;Presente tutti i giorni: ";
    if (count($attendance) == $days) {
        echo "SI<br><br>";
    } else {
        echo "NO<br><br>";
    }
}

echo str_repeat("-", 50) . "<br><br>";

foreach ($daily_attendance as $day => $present) {
    $absent = $number_of_students - $present;
    $percentage = number_format(getPercentage($present, $number_of_students), 1);
    echo "Giorno $day: $present present" . ($present === 1 ? 'e' : 'i') . " e {$absent} assent" . ($absent === 1 ? 'e' : 'i');
    echo " => " . str_replace(".", ",", $percentage) . "%<br>";
}

echo "<br>";

$max_presence = max($daily_attendance);
$top_days = [];
foreach($daily_attendance as $day => $present){
    if($present === $max_presence){
        $top_days[] = $day;
    }
}

echo "Giorni con più presenti: " . implode(", ", $top_days) . "<br><br>";

$total_students_presence = getTotalPresence($daily_attendance);

echo "Presenze totali: $total_students_presence <br><br>";


$max_students_presence = $days * $number_of_students;
$average_presence = number_format(getPercentage($total_students_presence, $max_students_presence), 1);

$presence_value = null;


$messages_list = [
    ["condition" => fn($x) => $x >= 0 && $x <= 24.99, "message" => "Scarsa"],
    ["condition" => fn($x) => $x >= 25 && $x <= 49.99, "message" => "Moderata"],
    ["condition" => fn($x) => $x >= 50 && $x <= 74.99, "message" => "Buona"],
    ["condition" => fn($x) => $x >= 75, "message" => "Ottima"],
];


foreach($messages_list as $row){
    if($row["condition"]($average_presence)){
        $presence_value = $row["message"];
    }
}

echo "Media presenze: " . str_replace(".", ",", $average_presence) . "% ($presence_value)<br><br>";


$max_attendance = count(max($students_attendance));
$best_students = [];

foreach($students_attendance as $student => $presence){
    if(count($presence) === $max_attendance){
        $best_students[] = $student;
    }
}

echo "Student" . (count($best_students) === 1 ? "e" : "i") . " con più presenze: " . implode(", ", $best_students) . "<br><br>";

echo var_dump($max_attendance);


function getTotalPresence(array $daily_attendance): int {
    return array_sum($daily_attendance);
}

function getPercentage(int $part, int $total): float {
    return ($part / $total) * 100;
}

?>