const botaomissao = document.getElementById('missao');
const botaovalores = document.getElementById('valores');
const botaovisao = document.getElementById('visao');
const logo = document.getElementById('LogoBitsToca');
const logooriginal = logo.innerHTML;
const missao = `<p>Apresentar e analisar jogos independentes, conectando jogadores a novas experiências e clássicos através de conteúdo autêntico e apaixonado..</p>`;
const valores = `<p>Nossos valores incluem inovação, acessibilidade, integridade e compromisso com a excelência em tudo o que fazemos.</p>`;
const visao = `<p>Nossa visão é ser líder global em soluções tecnológicas que transformam vidas e impulsionam o progresso social e econômico.</p>`;
function handlehoverin(content, specialClass = '') {
    logo.style.minHeight = '200px';
    logo.innerHTML = content;
    logo.classList.add('active-content');
    if (specialClass) {
        logo.classList.add(specialClass);
    }
}