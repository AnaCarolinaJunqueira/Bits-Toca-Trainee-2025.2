const botaomissao = document.getElementById('missao');
const botaovalores = document.getElementById('valores');
const botaovisao = document.getElementById('visao');
const container = document.getElementById('LogoBitsToca');
const logo = document.getElementById('logo-footer');
const missaocontent = document.getElementById('missao-footer');
const valorescontent = document.getElementById('valores-footer');
const visaocontent = document.getElementById('visao-footer');
const mvvcontent = [missaocontent, valorescontent, visaocontent];

function showcontent(content) {
  logo.hidden = true;
  mvvcontent.forEach((content) => {
    content.hidden = true;
    if (content) {
      content.hidden = false;
      container.style.minHeight = '200px';
    }
    else {
      logo.hidden = false;
      container.style.minHeight = '';
    }
  });
}

botaomissao.addEventListener('mouseenter', () => {
  showcontent(missao);
});
botaomissao.addEventListener('mouseleave', () => showcontent(logo));

botaovalores.addEventListener('mouseenter', () => {
  showcontent(valores);
});
botaovalores.addEventListener('mouseleave', () => showcontent(logo));

botaovisao.addEventListener('mouseenter', () => {
  showcontent(visao);
});
botaovisao.addEventListener('mouseleave', () => showcontent(logo));