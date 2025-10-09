<?php
// oportunidade.php

// Define a simple class for the User
class User {
    public $id;
    public $type;
    public $isActive;

    public function __construct($id, $type, $isActive) {
        $this->id = $id;
        $this->type = $type;
        $this->isActive = $isActive;
    }

    public function toArray() {
        return array("id" => $this->id, "type" => $this->type, "isActive" => $this->isActive);
    }
}

$regular_users = array();
$log_file = 'opp_log.txt';
$index_file = 'current_index.txt';

function log_action($message) {
    global $log_file;
    $timestamp = date("Y-m-d H:i:s");
    $log_message = "[" . $timestamp . "] " . $message . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

function get_current_index() {
    global $index_file;
    if (!file_exists($index_file)) {
        file_put_contents($index_file, '0');
    }
    return (int) file_get_contents($index_file);
}

function set_current_index($index) {
    global $index_file;
    file_put_contents($index_file, $index);
}

function get_user_data($opportunity_id) {
    global $regular_users;

    $user_url = "https://mdmidia.com.br/espo/api/v1/User";
    $headers = array(
        "X-Api-Key: cf10348d3467b17e454c736cf8035ee7"
    );

    log_action("Fetching users from $user_url");

    // Perform the GET request to fetch users
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $user_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        log_action("Successfully fetched users.");

        $data = json_decode($response, true);
        $users = array();
        foreach ($data['list'] as $item) {
            $users[] = new User($item['id'], $item['type'], $item['isActive']);
        }

        // Filter regular users with isActive as true
        $regular_users = array_filter($users, function($user) {
            return $user->type == 'regular' && $user->isActive;
        });

        if (!empty($regular_users)) {
            $current_index = get_current_index();
            // Get the current regular user based on the index
            $regular_user = array_values($regular_users)[$current_index];
            log_action("Selected regular user: " . json_encode($regular_user->toArray()));

            // Check the opportunity's assignedUserId
            $opp_url = "https://mdmidia.com.br/espo/api/v1/Opportunity/" . $opportunity_id;
            log_action("Checking opportunity ID $opportunity_id for assignedUserId");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $opp_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $opp_response = curl_exec($ch);
            $opp_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($opp_http_code == 200) {
                $opp_data = json_decode($opp_response, true);
                if ($opp_data['assignedUserId'] === null) {
                    log_action("assignedUserId is null. Proceeding with PUT request.");

                    // Prepare the PUT request
                    $put_headers = array(
                        "X-Api-Key: cf10348d3467b17e454c736cf8035ee7",
                        "Content-Type: application/json"
                    );
                    $put_data = json_encode(array("assignedUserId" => $regular_user->id));

                    log_action("Assigning user ID " . $regular_user->id . " to opportunity ID $opportunity_id");

                    // Perform the PUT request
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $opp_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $put_headers);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    $put_response = curl_exec($ch);
                    $put_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($put_http_code == 200) {
                        log_action("PUT request was successful.");
                    } else {
                        log_action("PUT request failed with status code: " . $put_http_code);
                        log_action("Response: " . $put_response);
                    }

                    // Update the current index for the next request
                    $current_index = ($current_index + 1) % count($regular_users);
                    set_current_index($current_index);
                } else {
                    log_action("assignedUserId is not null. Skipping opportunity ID $opportunity_id.");
                }
            } else {
                log_action("Failed to fetch opportunity. HTTP code: $opp_http_code");
            }
        }

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(array_map(function($user) {
            return $user->toArray();
        }, $users));
    } else {
        log_action("Failed to fetch users. HTTP code: $http_code");
        http_response_code($http_code);
        echo "Failed to fetch users";
    }
}

// Check if the 'id' parameter is set in the query string
if (isset($_GET['id'])) {
    $opportunity_id = $_GET['id'];
    log_action("Processing request for opportunity ID: $opportunity_id");
    get_user_data($opportunity_id);
} else {
    log_action("Missing id parameter");
    http_response_code(400);
    echo "Missing id parameter";
}
?>
