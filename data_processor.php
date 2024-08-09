<?php
include 'inventory.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed = ['csv' => 'text/csv', 'json' => 'application/json', 'xml' => 'text/xml'];
    $maxsize = 5 * 1024 * 1024; 
    $upload_dir = 'uploads/';
    $success_count = 0;
    $error_count = 0;
    $skipped_count = 0;
    $total_files = count($_FILES['files']['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $filename = $_FILES['files']['name'][$i];
        $filetype = $_FILES['files']['type'][$i];
        $filesize = $_FILES['files']['size'][$i];
        $tmp_name = $_FILES['files']['tmp_name'][$i];
        
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $error_count++;
            continue;
        }

        if ($filesize > $maxsize) {
            $error_count++;
            continue;
        }

        if (in_array($filetype, $allowed)) {
            $target_file = $upload_dir . basename($filename);

            if (file_exists($target_file)) {
                $skipped_count++;
                continue;
            } else {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $success_count++;
                    try {
                        
                        process_file($target_file, $ext);
                    } catch (Exception $e) {
                        $error_count++;
                    }
                } else {
                    $error_count++;
                }
            }
        } else {
            $error_count++;
        }
    }

    $message = "Upload Summary: <br> Successfully processed: $success_count <br> Skipped: $skipped_count <br> Errors: $error_count";
    echo $message;
} else {
    echo "No files were uploaded.";
}

function process_file($file, $ext) {
    switch ($ext) {
        case 'csv':
            $data = parse_csv($file);
            break;
        case 'json':
            $data = parse_json($file);
            break;
        case 'xml':
            $data = parse_xml($file);
            break;
        default:
            throw new Exception("Unsupported file format.");
    }

    $valid_records = [];
    $invalid_records = [];

    foreach ($data as $record) {
        if (validate_record($record)) {
            $valid_records[] = $record;
        } else {
            $invalid_records[] = $record;
        }
    }

    if (!empty($valid_records)) {
        upload($valid_records);
    }

    if (!empty($invalid_records)) {
        log_errors($invalid_records);
    }
}

function validate_record($record) {
    
    $required_fields = [
        'product_id' => 'integer',
        'product_name' => 'string',
        'category' => 'string',
        'price' => 'float',
        'stock' => 'integer'
    ];

    foreach ($required_fields as $field => $type) {
        if (!isset($record[$field])) {
            return false;
        }

        switch ($type) {
            case 'integer':
                if (!is_int($record[$field])) {
                    return false;
                }
                break;
            case 'string':
                if (!is_string($record[$field])) {
                    return false;
                }
                break;
            case 'float':
                if (!is_float($record[$field]) && !is_numeric($record[$field])) {
                    return false;
                }
                break;
            default:
                return false;
        }
    }

    return true;
}

function log_errors($invalid_records) {
    $error_log = 'error_log.txt';
    $current_errors = file_exists($error_log) ? file_get_contents($error_log) : '';
    $new_errors = '';

    foreach ($invalid_records as $record) {
        $new_errors .= json_encode($record) . PHP_EOL;
    }

    file_put_contents($error_log, $current_errors . $new_errors);
}

function parse_csv($file) {
    $csvData = array_map('str_getcsv', file($file));
    array_walk($csvData, function(&$a) use ($csvData) {
        $a = array_combine($csvData[0], $a);
    });
    array_shift($csvData); 
    return $csvData;
}

function parse_json($file) {
    $jsonData = file_get_contents($file);
    return json_decode($jsonData, true);
}

function parse_xml($file) {
    $xml = simplexml_load_file($file);
    $json = json_encode($xml);
    return json_decode($json, true)['product'];
}
?>