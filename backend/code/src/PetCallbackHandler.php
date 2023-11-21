<?php

namespace oszimt\petweightwatcher;

class PetCallbackHandler {
    public function __construct(
        private readonly DataReader $dataReader,
        private readonly DataWriter $dataWriter,
    ) {}

    /**
     * Wird benutzt um zu prüfen, ob der Server erreichbar ist
     */
    public function getHealthCheck(): void
    {
        echo json_encode(['status' => 'ok']);
    }

    /**
     * Schreibe die Daten eines neuen Haustieres in die CSV-Datei <br>
     * ex. <code>
     *  {
     *      "Mensch":
     *       {
     *          "min":"40.0",
     *          "avg":"70.0",
     *          "max":"90.0"
     *       }
     *  }
     * </code>
     */
    public function setPetData(): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $jsonBody = $this->getJsonBody();

        if ($this->validatePetData($jsonBody) === false) {
            echo json_encode(['status' => 'Fehler: Bitte geben Sie alle Argumente an: min, avg, max']);
            return;
        }

        // Der Name des Tieres ist der Key des Arrays
        $petName = array_keys($jsonBody)[0];

        if (array_key_exists($petName, $data)) {
            echo json_encode(['status' => 'Fehler: Tierart existiert bereits']);
            return;
        }

        $data[] = [
            'Tiername' => $petName,
            'Mindestgewicht' => $jsonBody[$petName]['min'],
            'Durchschnittsgewicht' => $jsonBody[$petName]['avg'],
            'Maximalgewicht' => $jsonBody[$petName]['max']
        ];

        $this->dataWriter->writeCSV('haustiere.csv', $data);
        echo json_encode(['status' => 'ok']);
    }

    private function validatePetData(array $jsonBody): bool
    {
        // Wir erwarten ein Array mit genau einem Element, unserem Tier
        if (count($jsonBody) !== 1) {
            return false;
        }

        //
        $petName = array_keys($jsonBody)[0];
        return array_key_exists('min', $jsonBody[$petName])
            && array_key_exists('avg', $jsonBody[$petName])
            && array_key_exists('max', $jsonBody[$petName]);
    }

    private function getJsonBody(): array
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return $data;
    }


    /**
     * @param string $name    Name des Haustieres
     * @param float  $gewicht Gewicht des Haustieres
     *
     * Zeigt das durchschnittliche Gewicht eines Haustieres an
     */
    public function getPetAverageWeight(string $name, float $gewicht): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $petName = $this->findPetByWeightAndType($data, $gewicht, $name);

        if ($petName) {
            echo json_encode(['name' => $petName]);
        } else {
            echo json_encode(['status' => 'Fehler: Kein Haustier gefunden']);
        }
    }

    /**
     * Zeigt alle vorhandenen Haustierarten an
     */
    public function getPetTypes(): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $petTypes = [];
        foreach ($data as $pet) {
            $petTypes[] = $pet['Tiername'];
        }
        echo json_encode($petTypes);
    }

    /**
     * Zeigt ein Haustier mit allen Daten an
     */
    public function getOnePetTypeWithAllData(string $name): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $petTypes = [];
        foreach ($data as $pet) {
            // groß/kleinschreibung wird ignoriert
            if (strtolower($pet['Tiername']) === strtolower($name)) {
                $petTypes[$pet['Tiername']] = [
                    'min' => $pet['Mindestgewicht'],
                    'avg' => $pet['Durchschnittsgewicht'],
                    'max' => $pet['Maximalgewicht']
                ];
            }
        }

        if (count($petTypes) > 0) {
            echo json_encode($petTypes);
        } else {
            echo json_encode(['status' => 'Fehler: Kein Haustier mit dem Namen gefunden']);
        }
    }

    /**
     * Shows all pet types with all data
     */
    public function getAllPetTypesWithAllData(): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $petTypes = [];
        foreach ($data as $pet) {
            $petTypes[$pet['Tiername']] = [
                'min' => $pet['Mindestgewicht'],
                'avg' => $pet['Durchschnittsgewicht'],
                'max' => $pet['Maximalgewicht']
            ];
        }
        echo json_encode($petTypes);
    }

    private function findPetByWeightAndType(array $pets, float $weight = null, string $type = null): ?float
    {
        foreach ($pets as $pet) {
            //if ($pet['gewicht'] == $weight && strtolower($pet['tierart']) == strtolower($type)) {
            if (strtolower($pet['Tiername']) == strtolower($type)) {
                return $pet['Durchschnittsgewicht'];
            }
        }
        return null;
    }
}