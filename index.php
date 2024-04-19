<!DOCTYPE html>
<html>
<head>
  <title>Podcast Player</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="styles.css?v=16">
</head>
<body>
  <nav>
    <div class="container">
      <a href="index.php" class="logo">Podcast Player</a>
    </div>
  </nav>

  <div id="search-container">
    <div class="container">
      <h1>Search for Podcasts</h1>
      <form action="index.php" method="get">
        <input type="text" id="search-term" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
      </form>
    </div>
  </div>

  <div id="search-results">
    <div class="container">
      <?php
      if (isset($_GET['search_term'])) {
        $searchTerm = urlencode($_GET['search_term']);
        $apiUrl = "https://itunes.apple.com/search?term={$searchTerm}&entity=podcast";

        $response = file_get_contents($apiUrl);
        $results = json_decode($response, true);

        if (isset($results['results'])) {
          foreach ($results['results'] as $podcast) {
            $title = $podcast['collectionName'];
            $description = $podcast['description'] ?? 'No description available';
            $artworkUrl = $podcast['artworkUrl600'] ?? $podcast['artworkUrl100'] ?? $podcast['artworkUrl30'] ?? '';
            $feedUrl = $podcast['feedUrl'];
            ?>
            <div class="podcast-item">
              <img src="<?php echo $artworkUrl; ?>" alt="<?php echo $title; ?>">
              <div class="podcast-info">
                <h2><a href="player.php?feed_url=<?php echo urlencode($feedUrl); ?>"><?php echo $title; ?></a></h2>
                <p><?php echo $description; ?></p>
              </div>
            </div>
            <?php
          }
        } else {
          echo '<p>No podcasts found.</p>';
        }
      }
      ?>
    </div>
  </div>

</body>
</html>