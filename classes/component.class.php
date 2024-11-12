<?php

class Component
{
    use DatabaseConnection;

    function salesOfEmployee($admin_id)
    {

        // SQL query to fetch data where admin_id matches and group by added_by with sum of amount
        $sql = "SELECT added_by, SUM(amount) AS amount FROM stock_out WHERE admin_id = ? GROUP BY added_by";

        // Prepare and bind
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $admin_id);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Close connections
        $stmt->close();

        return $data;
    }
}
