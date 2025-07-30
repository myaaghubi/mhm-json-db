<?php

namespace MHMJsonDB;

class MHMJsonDB
{
    private $filePath;

    public function __construct(string $fileName = '', string $path = '')
    {
        if (empty($fileName)) {
            $fileName = 'MHMJsonDB.json';
        }
        if (empty($path)) {
            $path = dirname(__FILE__, 2);
        }

        $this->filePath = ltrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    private function readData()
    {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

    // Write data to the JSON file
    private function writeData(array $data)
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Insert a new record
    public function insert(array $record)
    {
        $data = $this->readData();
        $record['id'] = uniqid(); // Generate a unique ID
        $data[] = $record;
        $this->writeData($data);
        return $record;
    }

    // Insert a new record
    public function insertTable(string $table, array $record)
    {
        $data = $this->readData();
        $record['id'] = uniqid(); // Generate a unique ID
        $record_[$table] = $record; 
        $data[] = $record_;
        $this->writeData($data);
        return $record_;
    }

    public function selectOne(array $whereColumnsData = []): array|null
    {
        $data = $this->readData();
        if (empty($whereColumnsData)) {
            return $data;
        }

        foreach ($data as $record) {
            $flag = true;
            foreach ($whereColumnsData as $column => $value) {
                if ($record[$column] != $value) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                return $record;
            }
        }
        return null;
    }

    // empty $whereColumnsData => select all
    public function select(array $whereColumnsData = []): array|null
    {
        $data = $this->readData();
        if (empty($whereColumnsData)) {
            return $data;
        }

        $selected = [];

        foreach ($data as $record) {
            $flag = true;
            foreach ($whereColumnsData as $column => $value) {
                if ($record[$column] != $value) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $selected[] = $record;
            }
        }
        return $selected;
    }

    // empty $whereColumnsData => delete all
    public function update(array $whereColumnsData = [], array $columnsNewData = []): bool
    {
        if (empty($columnsNewData)) {
            return false;
        }

        $data = $this->readData();
        if (empty($data)) {
            return false;
        }

        $cnt = count($data);
        for ($i = 0; $i < $cnt; $i++) {
            $record = $data[$i];
            $flag = true;
            foreach ($whereColumnsData as $column => $value) {
                if ($record[$column] != $value) {
                    $flag = false;
                    break;
                }
            }

            if ($flag) {
                foreach ($columnsNewData as $column => $value) {
                    $record[$column] = $value;
                }

                $data[$i] = $record;
                $this->writeData($data);
                return true;
            }
        }
        return false;
    }

    public function delete(array $whereColumnsData = []): int
    {
        $data = $this->readData();

        $deletesHappened = 0;
        $data = array_filter($data, function ($record) use ($whereColumnsData, &$deletesHappened) {
            foreach ($whereColumnsData as $column => $value) {
                if ($record[$column] != $value) {
                    return true;
                }
            }
            
            $deletesHappened++;
            return false;
        });
        $this->writeData(array_values($data));

        return $deletesHappened;
    }
}
