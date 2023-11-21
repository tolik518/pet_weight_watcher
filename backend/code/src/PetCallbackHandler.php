<?php

namespace oszimt\petweightwatcher;

class PetCallbackHandler {
    public function __construct(
        private DataReader $dataReader
    ) {}

    /**
     * @param string $name  Name of the pet
     * @param float  $gewicht Weight of the pet
     *
     * Shows the average weight of a pet
     */
    public function getPetAverageWeight(string $name, float $gewicht): void
    {
        $data = $this->dataReader->readCSV('haustiere.csv');
        $petName = $this->findPetByWeightAndType($data, $gewicht, $name);

        if ($petName) {
            echo json_encode(['name' => $petName]);
        } else {
            echo json_encode(['error' => 'Kein Haustier gefunden']);
        }
    }

    /**
     * Shows all pet types
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
     * Shows all pet types with all data
     */
    public function getPetTypesWithAllData(): void
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

    /**
     * @param array       $pets  Array of pets
     * @param float|null  $weight Weight of the pet
     * @param string|null $type Type of the pet
     *
     * @return mixed|null Returns the average weight of the pet, otherwise returns null
     */
    private function findPetByWeightAndType(array $pets, float $weight = null, string $type = null)
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