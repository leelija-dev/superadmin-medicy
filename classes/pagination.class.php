<?php

class Pagination
{
    use DatabaseConnection;

    function productsWithPagination()
    {
        // Number of records per page
        $recordsPerPage = 16;

        // Get the current page number from the URL, default to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the starting record for the current page
        $startFrom = ($page - 1) * $recordsPerPage;

        // Query to retrieve records for the current page
        $sql = "SELECT * FROM products ORDER BY added_on LIMIT $startFrom, $recordsPerPage";
        $result = $this->conn->query($sql);

        // Fetch the records
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM products";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";

        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page - 1) . "'>Previous</a> </li>";
        }

        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);

            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }

            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }

        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page + 1) . "'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }

        $paginationHTML .= "</ul>";

        // Close the database connection
        $this->conn->close();

        // Return the data and pagination HTML
        return json_encode(['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML]);
    }






    function productRequestWithPagination()
    {
        $recordsPerPage = 16;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startFrom = ($page - 1) * $recordsPerPage;

        $sql = "SELECT * FROM product_request WHERE new_prod_req_status = 1 ORDER BY requested_on LIMIT $startFrom, $recordsPerPage";
        $result = $this->conn->query($sql);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM product_request";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";

        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page - 1) . "'>Previous</a> </li>";
        }

        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);

            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }

            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }

        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page + 1) . "'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }

        $paginationHTML .= "</ul>";

        // Close the database connection
        $this->conn->close();

        // Return the data and pagination HTML
        return json_encode(['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML]);
    }










    function productsWithPaginationForUser($adminId)
    {
        // Number of records per page
        $recordsPerPage = 16;
        $recordsPerPage2 = 4;

        // Get the current page number from the URL, default to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the starting record for the current page
        $startFrom = ($page - 1) * $recordsPerPage;

        // Query to retrieve records for the current page FROM products table
        $productsTableSql = "SELECT * FROM products ORDER BY added_on LIMIT $startFrom, $recordsPerPage";
        $productResult = $this->conn->query($productsTableSql);

        // Fetch the records
        $products = [];
        while ($row = $productResult->fetch_assoc()) {
            $products[] = $row;
        }

        // Query to retrieve records for the current page FROM product_request table for curretn user
        $productReuquestTableSql = "SELECT * FROM product_request WHERE admin_id = '$adminId' AND 	old_prod_flag = 0 ORDER BY requested_on LIMIT $startFrom, $recordsPerPage2";
        $productRequestResult = $this->conn->query($productReuquestTableSql);

        // Fetch the records
        $productRequestData = [];
        while ($row = $productRequestResult->fetch_assoc()) {
            $productRequestData[] = $row;
        }


        $products = array_merge($products, $productRequestData);


        // Pagination links
        $countFromProductsSql = "SELECT COUNT(*) AS totalproducts FROM products";
        $result = $this->conn->query($countFromProductsSql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['totalproducts'];

        $countFromProductRequestSql = "SELECT COUNT(*) AS totalproductsrequest FROM product_request WHERE admin_id = '$adminId'";
        $result = $this->conn->query($countFromProductRequestSql);
        $row = $result->fetch_assoc();
        $totalRequestedRecords = $row['totalproductsrequest'];

        $totalRecords = intval($totalRecords) + intval($totalRequestedRecords);
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";

        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page - 1) . "'>Previous</a> </li>";
        }

        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);

            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }

            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }

        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page + 1) . "'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }

        $paginationHTML .= "</ul>";

        // Close the database connection
        $this->conn->close();

        // Return the data and pagination HTML
        return json_encode(['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML]);
    }





    function dataPagination($recordsPerPage, $table, $columnId = 'id')
    {

        // Get the current page number from the URL, default to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the starting record for the current page
        $startFrom = ($page - 1) * $recordsPerPage;

        // Query to retrieve records for the current page
        $sql = "SELECT * FROM $table LIMIT $startFrom, $recordsPerPage ORDER BY $columnId DESC";
        $result = $this->conn->query($sql);

        // Fetch the records
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";

        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page - 1) . "'>Previous</a> </li>";
        }

        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);

            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }

            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }

        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page + 1) . "'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }

        $paginationHTML .= "</ul>";

        // Close the database connection
        $this->conn->close();

        // Return the data and pagination HTML
        return ['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML];
    }




    function arrayPagination($myArr, $recordsPerPage = 16)
    {

        $goTo = CURRENT_URL;
        if ($this->hasQueryString(CURRENT_URL)) {

            // Parse the URL
            $parsed_url = parse_url(CURRENT_URL);

            // Parse the query string into an associative array
            parse_str($parsed_url['query'], $query_params);

            // Remove the 'page' parameter if it exists
            unset($query_params['page']);

            // Rebuild the query string without the 'page' parameter
            $new_query_string = http_build_query($query_params);

            // Construct the new URL
            $goTo = "{$parsed_url['scheme']}://{$parsed_url['host']}{$parsed_url['path']}?$new_query_string";

            $goTo = "$goTo&page";
        } else {
            $goTo = "$goTo?page";
        }
        // exit;

        if ($myArr != null && is_array($myArr) && count($myArr) > 0) {

            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            $startFrom = ($page - 1) * $recordsPerPage;

            $totalRecords = count($myArr);

            $totalPages = ceil($totalRecords / $recordsPerPage);

            $items = array_slice($myArr, $startFrom, $recordsPerPage);

            $paginationHTML = "<ul class='pagination'>";

            if ($page > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='$goTo=1'><i class='fas fa-chevron-left'></i><i class='fas fa-chevron-left'></i></a></li>";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='$goTo=" . ($page - 1) . "'><i class='fas fa-chevron-left'></i></a></li>";
            }

            // If total pages are less than or equal to 7, display all pages
            if ($totalPages <= 7) {
                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $page) ? ' active' : '';
                    $disabledClass = ($i == $page) ? " class='page-link shadow-none disabled'" : " class='page-link shadow-none'";

                    $paginationHTML .= "<li class='page-item{$activeClass}'>";
                    $paginationHTML .= "<a href='{$goTo}={$i}'{$disabledClass}>$i</a></li>";
                }
            } else {
                // Determine the range of pages to display
                $startPage = max(1, $page - 3);
                $endPage = min($totalPages, $page + 3);

                // If there are pages before the start of the current range
                if ($startPage > 1) {
                    $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='{$goTo}=1'>1</a></li>";
                    if ($startPage > 2) {
                        $paginationHTML .= "<li class='page-item disabled'><span class='page-link'>...</span></li>"; // Ellipsis
                    }
                }

                // Generate links for the current range of pages
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $activeClass = ($i == $page) ? ' active' : '';
                    $disabledClass = ($i == $page) ? " class='page-link shadow-none disabled'" : " class='page-link shadow-none'";

                    $paginationHTML .= "<li class='page-item{$activeClass}'>";
                    $paginationHTML .= "<a href='{$goTo}={$i}'{$disabledClass}>$i</a></li>";
                }

                // If there are pages after the end of the current range
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) {
                        $paginationHTML .= "<li class='page-item disabled'><span class='page-link'>...</span></li>"; // Ellipsis
                    }
                    $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='{$goTo}={$totalPages}'>$totalPages</a></li>";
                }
            }


            if ($page < $totalPages) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='$goTo=" . ($page + 1) . "'><i class='fas fa-chevron-right'></i></a></li>";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='$goTo=$totalPages'><i class='fas fa-chevron-right'></i><i class='fas fa-chevron-right'></i></a></li>";
            }

            $paginationHTML .= "</ul>";

            return json_encode(['status' => 1, 'totalitem' => $totalRecords, 'items' => $items, 'paginationHTML' => $paginationHTML]);
        } else {
            return json_encode(['status' => 0, 'totalProducts' => '', 'products' => '', 'paginationHTML' => '']);
        }
    }




    function hasQueryString($url)
    {
        $parsedUrl = parse_url($url);
        return isset($parsedUrl['query']);
    }

    // Example usage:
    // $currentUrl = "http://example.com/some/page?existingParam=oldValue";
    // $keyToUpdate = "page";
    // $newValue = "newPageValue";

    // $updatedUrl = updateQueryStringParameter($currentUrl, $keyToUpdate, $newValue);

    // echo $updatedUrl;

}
