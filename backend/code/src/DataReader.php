<?php

namespace oszimt\petweightwatcher;

class DataReader
{
    /**
     * @param string $filename Name des zu lesenden CSV-Files
     *
     * @return array Gibt ein Array von Zeilen zurück
     */
    public function readCSV(string $filename): array
    {
        $rows = [];
        $ressource = fopen($filename, "rb");

        if ($ressource) {
            $headers = $this->readCSVHeaders($ressource);
            $rows = $this->readCSVDataRows($ressource, $headers);
            fclose($ressource);
        }

        return $rows;
    }

    private function readCSVHeaders($ressource): array | false
    {
        return fgetcsv($ressource, 1000, ",");
    }

    private function readCSVDataRows($ressource, array $headers): array
    {
        $rows = [];
        while ($data = $this->readCSVHeaders($ressource)) {
            $row = $this->mapDataRowToHeaders($data, $headers);
            $rows[] = $row;
        }
        return $rows;
    }

    private function mapDataRowToHeaders(array $data, array $headers): array
    {
        $row = [];
        foreach ($headers as $index => $header) {
            $row[$header] = $data[$index] ?? null;
        }
        return $row;
    }
}