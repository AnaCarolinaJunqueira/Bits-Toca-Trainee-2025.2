const sidebar = document.getElementById('sidebar');

sidebar.addEventListener('mouseenter', function() {
    sidebar.classList.add('open-sidebar'); // abre
});

sidebar.addEventListener('mouseleave', function() {
    sidebar.classList.remove('open-sidebar'); // fecha
});

const open_btn = document.getElementById('open_btn') ;

open_btn.addEventListener('click', ()=> {
    if(window.innerWidth <= 440) {
        sidebar.classList.toggle('open-sidebar'); // abre
    }

});
