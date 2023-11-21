<?php

namespace oszimt\petweightwatcher;

class DataWriter
{
    /**
     * @param string $filename Name des zu schreibenden CSV-Files
     * @param array  $data     Array von Zeilen mit Daten zum Schreiben
     */
    public function writeCSV(string $filename, array $data): void
    {
        $ressource = fopen($filename, "wb");

        if ($ressource) {
            $this->writeCSVHeaders($ressource, $data);
            $this->writeCSVDataRows($ressource, $data);
            fclose($ressource);
        }
    }

    private function writeCSVHeaders($ressource, array $data): void
    {
        fputcsv($ressource, array_keys($data[0]));
    }

    private function writeCSVDataRows($ressource, array $data): void
    {
        foreach ($data as $row) {
            fputcsv($ressource, $row);
        }
    }
}