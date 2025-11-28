<?php
try {
    include '../connect.php'; 
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $query = htmlspecialchars($query); 

    // Prepare SQL query with UNION
    $sql = "SELECT CONCAT(artist_fname, ' ', artist_lname) AS name, penname, 'artist' AS type FROM Artist 
            WHERE artist_fname LIKE ? OR artist_lname LIKE ? OR penname LIKE ?
            UNION
            SELECT art_name AS name, NULL AS penname, 'artwork' AS type FROM Artwork
            WHERE art_name LIKE ? 
            LIMIT 5";

    // Prepare the SQL statement
    if ($stmt = $pdo->prepare($sql)) {
        $likeQuery = "%" . $query . "%";
        $stmt->execute([$likeQuery, $likeQuery, $likeQuery, $likeQuery]);
        $results = $stmt->fetchAll();

        // Check if there are any results
        if (count($results) > 0) {
            foreach ($results as $result) {
                if ($result['type'] === 'artist') {
                    echo "<div class='suggestion-item' onclick='selectSuggestion(\"{$result['name']}\")'>{$result['name']} (Artist)</div>";
                } elseif ($result['type'] === 'artwork') {
                    echo "<div class='suggestion-item' onclick='selectSuggestion(\"{$result['name']}\")'>{$result['name']} (Artwork)</div>";
                }
            }
        } else {
            echo "<div class='suggestion-item'>No results found</div>";
        }
    } else {
        echo "<div class='suggestion-item'>Error preparing the statement</div>";
    }
}
?>
