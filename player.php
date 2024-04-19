<?php
if (isset($_GET['feed_url'])) {
  $feedUrl = $_GET['feed_url'];
  $xml = simplexml_load_file($feedUrl);

  $podcast = [
    'title' => (string) $xml->channel->title,
    'description' => (string) $xml->channel->description,
    'artwork' => (string) $xml->channel->image->url,
    'episodes' => [],
  ];

  foreach ($xml->channel->item as $item) {
    $episode = [
      'title' => (string) $item->title,
      'description' => (string) $item->description,
      'audioUrl' => (string) $item->enclosure['url'],
    ];
    $podcast['episodes'][] = $episode;
  }
} else {
  header('Location: index.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Podcast Player</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="styles.css?v=1791">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<nav>
  <div class="container">
  <a href="index.php" class="logo">Podcast Player</a>
    <ul class="nav-items">
      <li><a href="index.php">Home</a></li>
      <li><a href="https://pocketbarista.uk/pod/index.php?search_term=politics">Politics</a></li>
      <li><a href="https://pocketbarista.uk/pod/index.php?search_term=music">Music</a></li>
      <li><a href="https://pocketbarista.uk/pod/index.php?search_term=comedy">Comedy</a></li>
      <li><a href="https://pocketbarista.uk/pod/index.php?search_term=BBC">BBC</a></li>
    </ul>
    <div class="nav-toggle">
      <i class="fas fa-bars"></i>
    </div>
  </div>
</nav>

  <div id="podcast-player">
    <div class="container">
      <div class="player-info">
        <img id="podcast-artwork" src="<?php echo $podcast['artwork']; ?>" alt="Podcast Artwork">
        <div class="podcast-details">
          <h1 id="podcast-title"><?php echo $podcast['title']; ?></h1>
          <p id="podcast-description"><?php echo $podcast['description']; ?></p>
        </div>
      </div>
      <div id="player-container">
        <audio id="audio-player" controls></audio>
        <div id="player-controls">
          <button id="prev-button"><i class="fas fa-step-backward"></i></button>
          <button id="play-pause-button"><i class="fas fa-play"></i></button>
          <button id="next-button"><i class="fas fa-step-forward"></i></button>
        </div>
      </div>
      <div id="episode-list">
        <?php foreach ($podcast['episodes'] as $index => $episode) { ?>
          <div class="episode" data-audio-url="<?php echo $episode['audioUrl']; ?>" data-index="<?php echo $index; ?>">
            <h3><?php echo $episode['title']; ?></h3>
            <p><?php echo $episode['description']; ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const episodeElements = document.querySelectorAll('.episode');
    const audioPlayer = document.getElementById('audio-player');
    const playPauseButton = document.getElementById('play-pause-button');

    // Automatically select and play the first episode if no episode is selected
    if (!document.querySelector('.episode.active')) {
      const firstEpisode = episodeElements[0];
      if (firstEpisode) {
        const audioUrl = firstEpisode.dataset.audioUrl;
        const index = firstEpisode.dataset.index;
        playEpisode(audioUrl, index);
      }
    }

    episodeElements.forEach(episode => {
      episode.addEventListener('click', () => {
        const audioUrl = episode.dataset.audioUrl;
        const index = episode.dataset.index;
        playEpisode(audioUrl, index);
      });
    });

    function playEpisode(audioUrl, index) {
      audioPlayer.src = audioUrl;
      audioPlayer.play();
      episodeElements.forEach((episode, i) => {
        if (i == index) {
          episode.classList.add('active');
        } else {
          episode.classList.remove('active');
        }
      });
      updatePlayPauseButton();
    }

    function updatePlayPauseButton() {
      if (audioPlayer.paused) {
        playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
      } else {
        playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
      }
    }

    audioPlayer.addEventListener('play', updatePlayPauseButton);
    audioPlayer.addEventListener('pause', updatePlayPauseButton);

    playPauseButton.addEventListener('click', togglePlayPause);

    function togglePlayPause() {
      if (audioPlayer.paused) {
        audioPlayer.play();
      } else {
        audioPlayer.pause();
      }
    }

    const prevButton = document.getElementById('prev-button');
    const nextButton = document.getElementById('next-button');

    prevButton.addEventListener('click', playPrevEpisode);
    nextButton.addEventListener('click', playNextEpisode);

    function playPrevEpisode() {
      const currentEpisode = document.querySelector('.episode.active');
      const prevEpisode = currentEpisode.previousElementSibling;

      if (prevEpisode) {
        const audioUrl = prevEpisode.dataset.audioUrl;
        const index = prevEpisode.dataset.index;
        playEpisode(audioUrl, index);
      }
    }

    function playNextEpisode() {
      const currentEpisode = document.querySelector('.episode.active');
      const nextEpisode = currentEpisode.nextElementSibling;

      if (nextEpisode) {
        const audioUrl = nextEpisode.dataset.audioUrl;
        const index = nextEpisode.dataset.index;
        playEpisode(audioUrl, index);
      }
    }
  });
</script>
</body>
</html>