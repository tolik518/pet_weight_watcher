<?php

namespace oszimt\petweightwatcher;

class DataWriter
{
    /**
     * @param string $filename Name of the CSV file to read
     * @param array  $data     Array of rows to write to the CSV file
     *
     * @return void Returns an array of rows
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