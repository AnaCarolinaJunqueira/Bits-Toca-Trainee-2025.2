var allowedTetraKeys = {
  84: 't',
  69: 'e',
  82: 'r',
  65: 'a'
};

var secretCode = ['t', 'e', 't', 'r', 'a'];
var secretCodePosition = 0;

document.addEventListener('keydown', function(e) {
  var key = allowedTetraKeys[e.keyCode];
  var requiredKey = secretCode[secretCodePosition];

  if (key == requiredKey) {
    secretCodePosition++;

    if (secretCodePosition == secretCode.length) {
      activateTetraCheats();
      secretCodePosition = 0;
    }
  } else {
    secretCodePosition = 0;
  }
});

function activateTetraCheats() {
  if (document.getElementById('video-overlay')) return;

  const overlay = document.createElement('div');
  overlay.id = 'video-overlay';
  Object.assign(overlay.style, {
    position: 'fixed',
    top: '0',
    left: '0',
    width: '100vw',
    height: '100vh',
    backgroundColor: 'rgba(0, 0, 0, 0.9)',
    zIndex: '99999',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    backdropFilter: 'blur(5px)'
  });

  const closeBtn = document.createElement('button');
  closeBtn.innerText = 'X';
  Object.assign(closeBtn.style, {
    position: 'absolute',
    top: '20px',
    right: '20px',
    zIndex: '100000',
    width: '50px',
    height: '50px',
    backgroundColor: '#fff',
    color: '#000',
    border: 'none',
    borderRadius: '50%',
    fontWeight: 'bold',
    fontSize: '1.5rem',
    cursor: 'pointer',
    boxShadow: '0 0 10px rgba(255,255,255,0.5)'
  });

  closeBtn.onclick = () => {
      document.body.removeChild(overlay);
  };

  overlay.appendChild(closeBtn);

  const videoWrapper = document.createElement('div');
  Object.assign(videoWrapper.style, {
    position: 'relative',
    width: '80%',
    maxWidth: '900px',
    aspectRatio: '16 / 9',
    boxShadow: '0 0 20px rgba(0,0,0,0.8)'
  });

  const video = document.createElement('video');
  video.id = 'tetra-video';
  video.src = '/public/assets/movies/video_flamengo.mp4';
  video.autoplay = true;
  video.controls = false;
  video.volume = 0.3;
  video.playsInline = true;

  Object.assign(video.style, {
    width: '100%',
    height: '100%',
    display: 'block'
  });

  video.onended = () => {
      if (document.body.contains(overlay)) {
          document.body.removeChild(overlay);
      }
  };

  videoWrapper.appendChild(video);
  overlay.appendChild(videoWrapper);
  document.body.appendChild(overlay);
}