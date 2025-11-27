const sidebar = document.getElementById('sidebar');

function opensidebar() {
    sidebar.classList.add('open-sidebar'); // abre
};

function closesidebar() {
    sidebar.classList.remove('open-sidebar'); // fecha
};

const open_btn = document.getElementById('open_btn') ;

function enabledeskopsidebar() 
{
    sidebar.addEventListener('mouseenter', opensidebar);
    sidebar.addEventListener('mouseleave', closesidebar);
}

function disablesidebar() //Desabilitar quando estiver na versão mobile
{
    sidebar.removeEventListener('mouseenter', opensidebar);
    sidebar.removeEventListener('mouseleave', closesidebar);
}

function enablemobilesidebar()
{
    open_btn.addEventListener('click', togglemobilesidebar);
}

function disablemobilesidebar()
{
    open_btn.removeEventListener('click', togglemobilesidebar);
}

function togglemobilesidebar() 
{
    sidebar.classList.toggle('open-sidebar'); // abre

}

function windowcheck()
{
    if(window.innerWidth<=440)
    {
        enablemobilesidebar();
        disablesidebar();
    } else
    {
        enabledeskopsidebar();
        disablemobilesidebar();
    }    
}

windowcheck();
window.addEventListener('resize', windowcheck); //Confere se o tamanho está mudando e chama windowcheck

