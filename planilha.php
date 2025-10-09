<?php
// Function to log actions to a log file
function logAction($message) {
    $logFile = 'log_planilha.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Ensure the Planilhas directory exists
if (!is_dir('Planilhas')) {
    mkdir('Planilhas', 0777, true);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $jsonData = file_get_contents('php://input');
    
    // Log the received raw POST data for debugging
    logAction("Received raw POST data: " . $jsonData);
    
    // Decode the JSON data
    $data = json_decode($jsonData, true);
    
    // Check if JSON decoding succeeded
    if (json_last_error() !== JSON_ERROR_NONE) {
        $errorMessage = "JSON decode error: " . json_last_error_msg();
        logAction($errorMessage);
        
        echo json_encode(["status" => "error", "message" => $errorMessage]);
        exit;
    }
    
    // Log the decoded data for debugging
    logAction("Decoded JSON data: " . json_encode($data));
    
    // Check if the necessary keys exist in the decoded data
    if (isset($data['GClid'], $data['Email'], $data['Valor'])) {
        // Define the CSV file path
        $filePath = 'Planilhas/conversoesoffline.csv';
        
        // Get the current date
        $currentDate = date('Y-m-d');
        $fileExists = file_exists($filePath);
        $fileModificationDate = null;
        
        // Check if the CSV file already exists and extract the modification date
        if ($fileExists) {
            // Open the file for reading
            $file = fopen($filePath, 'r');
            $headerLine = fgets($file);
            $firstDataLine = fgets($file);
            fclose($file);
            
            // Extract the date from the first data line if it exists
            if ($firstDataLine) {
                $columns = explode(",", $firstDataLine);
                if (count($columns) > 2) {
                    // Assuming the third column is the conversion time
                    $conversionDate = explode(' ', $columns[2])[0];
                    $fileModificationDate = date('Y-m-d', strtotime($conversionDate));
                }
            }
        }
        
        // If the file's date is different from the current date, rename the file
        if ($fileExists && $fileModificationDate !== $currentDate) {
            $newFilePath = "Planilhas/conversoesoffline_{$fileModificationDate}.csv";
            rename($filePath, $newFilePath);
            logAction("Renamed existing file to $newFilePath");
            $fileExists = false; // Mark as not existing to create a new file
        }
        
        // Define the header and the first line content
        $header = "Parameters:TimeZone=-0300,,,,\n";
        $columns = "Google Click ID,Conversion Name,Conversion Time,Conversion Value,Conversion Currency\n";
        
        // Define the conversion name
        $conversionName = "SegurosImediatoOffline";
        
        // Get the current time and format it
        $conversionTime = date('Y/m/d H:i:s');
        
        // Prepare the row data
        $rowData = implode(",", [
            $data['GClid'],
            $conversionName,
            $conversionTime,
            $data['Valor'],
            "BRL"
        ]) . "\n";
        
        // Open the CSV file for appending if it exists, otherwise create it
        $file = fopen($filePath, $fileExists ? 'a' : 'w');
        
        // Write the header line if the file is newly created
        if (!$fileExists) {
            fwrite($file, $header);
            fwrite($file, $columns);
        }
        
        // Write the row data
        fwrite($file, $rowData);
        
        // Close the file
        fclose($file);
        
        logAction("Appended data to CSV file: " . json_encode($rowData));
        
        // Send a success response
        echo json_encode(["status" => "success", "message" => "CSV file updated successfully."]);
    } else {
        $errorMessage = "Invalid data. Required keys are missing.";
        logAction($errorMessage);
        
        // Send an error response if the required keys are missing
        echo json_encode(["status" => "error", "message" => $errorMessage]);
    }
} else {
    $errorMessage = "Invalid request method.";
    logAction($errorMessage);
    
    // Send an error response if the request method is not POST
    echo json_encode(["status" => "error", "message" => $errorMessage]);
}
?>
