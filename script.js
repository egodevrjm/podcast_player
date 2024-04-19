document.addEventListener('DOMContentLoaded', () => {
    const episodeElements = document.querySelectorAll('.episode');
    episodeElements.forEach(episode => {
      episode.addEventListener('click', () => {
        const audioUrl = episode.dataset.audioUrl;
        playEpisode(audioUrl);
      });
    });
  });
  
  function playEpisode(audioUrl) {
    const audioPlayer = document.getElementById('audio-player');
    audioPlayer.src = audioUrl;
    audioPlayer.play();
  }