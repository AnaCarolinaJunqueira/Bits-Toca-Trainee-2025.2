const botaomissao = document.getElementById('missao');
const botaovalores = document.getElementById('valores');
const botaovisao = document.getElementById('visao');
const logo = document.getElementById('LogoBitsToca');
const logooriginal = logo.innerHTML;
const missao = `<p>Apresentar e analisar jogos independentes, conectando jogadores a novas experiências e clássicos através de conteúdo autêntico e apaixonado..</p>`;
const valores = `    <p><strong>Comunidade:</strong> Construção de um espaço acolhedor, inclusivo e respeitoso.</p>
        <p><strong>Paixão:</strong> Amor pela arte e criatividade dos jogos independentes.</p>
        <p><strong>Autenticidade:</strong> Análises e histórias baseadas em experiências reais de jogo.</p>
        <p><strong>Curiosidade:</strong> Incentivo à descoberta de partidas desconhecidas.</p>`;
const visao = `<p>Ser o ponto de encontro preferido da comunidade de jogos indie, reconhecido pela qualidade de suas recomendações e por ser um espaço de troca genuína.</p>`;
function handlehoverin(content, specialClass = '') {
  logo.style.minHeight = '200px';
  logo.innerHTML = content;
  logo.classList.add('active-content');
  if (specialClass) {
    logo.classList.add(specialClass);
  }
}
function handlehoverout() {
  logo.innerHTML = logooriginal;
  logo.classList.remove('active-content', 'centralizado', 'valores');
  logo.style.minHeight = '';

}

botaomissao.addEventListener('mouseenter', () => {
  handlehoverin(missao, 'centralizado');
});
botaomissao.addEventListener('mouseleave', handlehoverout);

botaovalores.addEventListener('mouseenter', () => {
  handlehoverin(valores, 'valores');
});
botaovalores.addEventListener('mouseleave', handlehoverout);

botaovisao.addEventListener('mouseenter', () => {
  handlehoverin(visao, 'centralizado');
});
botaovisao.addEventListener('mouseleave', handlehoverout);