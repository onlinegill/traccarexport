<?php
// Define your Traccar API username and password
$traccar_username = 'traccar_user';
$traccar_password = 'traccar_password';

// Traccar API endpoint for devices
$traccar_api_url = 'http://your_traccar_domain/api/devices';

// Function to fetch data from Traccar API
function fetchTraccarData($url, $username, $password) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Fetch devices from Traccar using Traccar API credentials
$devices = fetchTraccarData($traccar_api_url, $traccar_username, $traccar_password);

// CSV file paths
$offline_csv_file = 'offline_devices.csv';
$online_csv_file = 'online_devices.csv';

// Open CSV files for writing (offline and online devices)
$offline_csv_handle = fopen($offline_csv_file, 'w');
$online_csv_handle = fopen($online_csv_file, 'w');

// Write CSV headers
fputcsv($offline_csv_handle, array('Device ID', 'Identifier', 'Name', 'Status'));
fputcsv($online_csv_handle, array('Device ID', 'Identifier', 'Name', 'Status'));

// Loop through devices and write them to respective CSV files
foreach ($devices as $device) {
    $data = array($device['id'], $device['uniqueId'], $device['name'], $device['status']);
    
    if ($device['status'] == 'offline') {
        fputcsv($offline_csv_handle, $data);
    } else {
        fputcsv($online_csv_handle, $data);
    }
}

// Close CSV files
fclose($offline_csv_handle);
fclose($online_csv_handle);

echo "Devices exported successfully.";

// Provide download links for the CSV files
echo '<br><a href="'.$offline_csv_file.'" download>Download Offline Devices CSV file</a>';
echo '<br><a href="'.$online_csv_file.'" download>Download Online Devices CSV file</a>';
?>
