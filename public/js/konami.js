// konami code

var allowedKeys = {
  37: 'left',
  38: 'up',
  39: 'right',
  40: 'down',
  65: 'a',
  66: 'b'
};

var konamiCode = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];

var konamiCodePosition = 0;

document.addEventListener('keydown', function(e) {
  var key = allowedKeys[e.keyCode];
  var requiredKey = konamiCode[konamiCodePosition];

  if (key == requiredKey) {
    konamiCodePosition++;

    if (konamiCodePosition == konamiCode.length) {
      activateCheats();
      konamiCodePosition = 0;
    }
  } else {
    konamiCodePosition = 0;
  }
});


// doom por favor funciona

function activateCheats() {
  if (document.getElementById('doom-overlay')) return;

  // Overlay
  const overlay = document.createElement('div');
  overlay.id = 'doom-overlay';
  overlay.style.position = 'fixed';
  overlay.style.top = '0';
  overlay.style.left = '0';
  overlay.style.width = '100vw';
  overlay.style.height = '100vh';
  overlay.style.backgroundColor = '#000';
  overlay.style.zIndex = '99999';
  overlay.style.display = 'flex';
  overlay.style.flexDirection = 'column';
  overlay.style.alignItems = 'center';
  overlay.style.justifyContent = 'center';

  // Close button
  const closeBtn = document.createElement('button');
  closeBtn.innerText = 'FECHAR DOOM';
  closeBtn.style.position = 'absolute';
  closeBtn.style.top = '20px';
  closeBtn.style.right = '20px';
  closeBtn.style.zIndex = '100000';
  closeBtn.style.padding = '10px 20px';
  closeBtn.style.backgroundColor = '#B83556';
  closeBtn.style.color = '#fff';
  closeBtn.style.border = 'none';
  closeBtn.style.fontFamily = "'VT323', monospace";
  closeBtn.style.fontSize = '1.5rem';
  closeBtn.style.cursor = 'pointer';

  closeBtn.onclick = () => {
      document.body.removeChild(overlay);
      window.location.reload();
  };

  overlay.appendChild(closeBtn);

  // Fullscreen button
  const fsBtn = document.createElement('button');
  fsBtn.innerText = 'TELA CHEIA';
  fsBtn.style.position = 'absolute';
  fsBtn.style.top = '20px';
  fsBtn.style.right = '220px'; // Positioned to the left of the close button
  fsBtn.style.zIndex = '100000';
  fsBtn.style.padding = '10px 20px';
  fsBtn.style.backgroundColor = '#55768C';
  fsBtn.style.color = '#fff';
  fsBtn.style.border = 'none';
  fsBtn.style.fontFamily = "'VT323', monospace";
  fsBtn.style.fontSize = '1.5rem';
  fsBtn.style.cursor = 'pointer';

  fsBtn.onclick = () => {
      const canvas = document.getElementById('jsdos-game');
      if (canvas) {
          if (canvas.requestFullscreen) {
              canvas.requestFullscreen();
          } else if (canvas.webkitRequestFullscreen) { /* Safari */
              canvas.webkitRequestFullscreen();
          } else if (canvas.msRequestFullscreen) { /* IE11 */
              canvas.msRequestFullscreen();
          }
      }
  };

  overlay.appendChild(fsBtn);

  // Canvas for js-dos
  const canvas = document.createElement('canvas');
  canvas.id = 'jsdos-game';
  canvas.width = 960;
  canvas.height = 600;

  // Mobile resize
  if (window.innerWidth < 650) {
      canvas.style.width = '90%';
      canvas.style.height = 'auto';
      canvas.style.aspectRatio = '320/200';
  }

  overlay.appendChild(canvas);
  document.body.appendChild(overlay);

  // Load js-dos
  const script = document.createElement('script');
  script.src = "https://js-dos.com/6.22/current/js-dos.js";

  script.onload = () => {
    Dos(canvas, {
        wdosboxUrl: "https://js-dos.com/6.22/current/wdosbox.js",
    }).ready((fs, main) => {
        fs.extract("https://js-dos.com/cdn/upload/DOOM-@evilution.zip").then(() => {
            main(["-c", "cd DOOM", "-c", "DOOM.EXE"]);
        });
    });
  };

  document.head.appendChild(script);
}