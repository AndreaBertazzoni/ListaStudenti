<?php

$presenze_studenti = ["Andrea Bertazzoni" => [], "Giada Altomare" => [], "Federico Carrassi" => [], "Antonino Beninato" => [],];

$numero_studenti = count($presenze_studenti);

$giorni = 7;

$lista_presenze = array_fill_keys(range(1, $giorni), 0);


for ($i = 1; $i <= $giorni; $i++) {
    foreach ($presenze_studenti as $studente => $presenza) {
        $random_bool = random_int(0, 1);
        if ($random_bool) {
            $presenze_studenti[$studente][] = $i;
            $lista_presenze[$i]++;
        }
    }
}

foreach ($presenze_studenti as $studente => $presenza) {
    echo "$studente:\n";
    if (!count($presenza) == 0) {
        echo "\tPresente nei giorni: " . implode(",", $presenza) . "\n";
    }
    echo "\t" . count($presenza) . " presenz" . ((count($presenza) === 1 ? 'a' : 'e')) . "\n";
    echo "\tPresente tutti i giorni: ";
    if (count($presenza) == $giorni) {
        echo "SI\n\n";
    } else {
        echo "NO\n\n";
    }
}

echo str_repeat("-", 50) . "\n\n";


foreach ($lista_presenze as $giorno => $presenze) {
    $presenti = $presenze;
    $assenti = $numero_studenti - $presenti;
    echo "Giorno $giorno: $presenti present" . ($presenti === 1 ? 'e' : 'i') . " e {$assenti} assent" . ($assenti === 1 ? 'e' : 'i') . " => \n";
}
