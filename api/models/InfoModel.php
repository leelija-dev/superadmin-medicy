<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';

use DatabaseConnection\DatabaseConnection;

class Info {
    private $conn;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
    }

    public function getAllInfos() {
        $query = "SELECT info_name, info_value FROM info";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInfoByName($infoname) {
        $query = "SELECT * FROM info WHERE info_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $infoname);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createInfo($data) {
        $query = "INSERT INTO info (logo, name, description, url, version, release_date, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssssss', $data['logo'], $data['name'], $data['description'], $data['url'], $data['version'], $data['release_date']);
        return $stmt->execute();
    }

    public function updateInfo($id, $data) {
        $query = "UPDATE info SET 
                    logo = ?, 
                    name = ?, 
                    description = ?, 
                    url = ?, 
                    version = ?, 
                    release_date = ?, 
                    updated_at = NOW() 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssssssi', $data['logo'], $data['name'], $data['description'], $data['url'], $data['version'], $data['release_date'], $id);
        return $stmt->execute();
    }

    public function deleteInfo($id) {
        $query = "DELETE FROM info WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
