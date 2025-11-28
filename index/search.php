<?php
include '../connect.php';

// Check if a search query was provided
if (isset($_GET['query'])) {
    $query = trim($_GET['query']); 

    if (!empty($query)) {
        // Prepare the search SQL query
        $sql = "SELECT art_id, art_name, artist_name, pen_name 
                FROM Artwork 
                LEFT JOIN Artist ON Artwork.artist_id = Artist.artist_id 
                WHERE art_name LIKE ? OR artist_name LIKE ? OR pen_name LIKE ?
                ORDER BY artist_name";

        if ($stmt = $conn->prepare($sql)) {
            $param = "%" . $query . "%"; 
            $stmt->bind_param("sss", $param, $param, $param); 

            // Execute the query
            if ($stmt->execute()) {
                $result = $stmt->get_result(); 

                // Check if any rows were returned
                if ($result->num_rows > 0) {
                    echo "<h2>Search Results for: " . htmlspecialchars($query) . "</h2>";
                    echo "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>Artist: " . htmlspecialchars($row['artist_name']) .
                            " (Pen Name: " . htmlspecialchars($row['pen_name']) . ") - " .
                            "Artwork: " . htmlspecialchars($row['art_name']) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No results found for: " . htmlspecialchars($query) . "</p>";
                }
            } else {
                echo "<p>Error executing query: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Could not prepare the search statement: " . htmlspecialchars($conn->error) . "</p>";
        }
    } else {
        echo "<p>Please enter a search term.</p>";
    }
} else {
    echo "<p>Invalid search request.</p>";
}

// Close the database connection
$conn->close();
