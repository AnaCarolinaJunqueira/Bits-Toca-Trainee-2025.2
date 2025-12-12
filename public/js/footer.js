const botaomissao = document.getElementById('missao');
const botaovalores = document.getElementById('valores');
const botaovisao = document.getElementById('visao');
const container = document.querySelector('.LogoBitsToca');
const logo = document.getElementById('logo-footer');
const missaocontent = document.getElementById('missao-footer');
const valorescontent = document.getElementById('valores-footer');
const visaocontent = document.getElementById('visao-footer');
const ismobile = window.innerWidth <= 768;
let activecontent = logo;

const mvvcontent = [missaocontent, valorescontent, visaocontent];

function showMvvContent(contentToShow) {
  logo.hidden = true;

  mvvcontent.forEach((item) => {
    item.hidden = true;
  });

  if (contentToShow) {
    contentToShow.hidden = false;

    container.classList.add('active-content');

    if (contentToShow === valorescontent) {
      container.classList.add('valores');
    } else {
      container.classList.remove('valores');
    }
  }
  activecontent = contentToShow;
}

function showLogo() {
  mvvcontent.forEach((item) => {
    item.hidden = true;
  });

  logo.hidden = false;

  container.classList.remove('active-content',
     'valores');
  activecontent = logo;
}

  if (!ismobile) {
    botaomissao.addEventListener('mouseenter', () => {
      showMvvContent(missaocontent);
    });
    botaomissao.addEventListener('mouseleave', () => {
      showLogo();
    });

    botaovalores.addEventListener('mouseenter', () => {
      showMvvContent(valorescontent);
    });
    botaovalores.addEventListener('mouseleave', () => {
      showLogo();
    });

    botaovisao.addEventListener('mouseenter', () => {
      showMvvContent(visaocontent);
    });
    botaovisao.addEventListener('mouseleave', () => {
      showLogo();
    });

  } else {
    botaomissao.addEventListener('click', () => {
      if(missaocontent.hidden) {
        showMvvContent(missaocontent);
      }
      else {
        showLogo();
      }

    });
    botaovalores.addEventListener('click', () => {
     if(valorescontent.hidden) {
        showMvvContent(valorescontent);
      }
      else {
        showLogo();
      }
    });
    botaovisao.addEventListener('click', () => {
      if(visaocontent.hidden) {
        showMvvContent(visaocontent);
      }
      else {
        showLogo();
      }
    });
  }
  showLogo();