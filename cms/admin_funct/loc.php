<?php
function get_location(){
    require_once 'api/session.php';

    $ip_address = /*$_SERVER['REMOTE_ADDR']*/'142.251.36.110';//ak by to nebol localhost
    
    $location_data = file_get_contents("http://ipinfo.io/{$ip_address}/json");
    $location_data = json_decode($location_data, true);
    
    $country = isset($location_data['country']) ? $location_data['country'] : 'Unknown';
    $city = isset($location_data['city']) ? $location_data['city'] : 'Unknown';
    
    $stmt = $conn->prepare("INSERT INTO user_locations (ip_address, country, city) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip_address, $country, $city);
    
    $stmt->execute();
    $stmt->close();
    $conn->close();
    //echo "<br>" . "Country: " . $country . "<br>" . "City: " . $city;

}
?>
<?php
function read_location_data() {
    require_once 'api/session.php';

    $query = "SELECT country_code, user_count FROM users";
    $result = $conn->query($query);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[$row['country_code']] = $row['user_count'];
    }

    $conn->close();
    echo json_encode($data);
}
?>