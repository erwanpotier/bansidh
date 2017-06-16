
var el = document.getElementById('headline');
var elTop = el.getBoundingClientRect().top - document.body.getBoundingClientRect().top;

window.addEventListener('scroll', function(){
    if (document.documentElement.scrollTop > elTop){
        el.style.position = 'fixed';
        el.style.width = '100%';
        el.style.top = '0px';
    }
    else
    {
        el.style.position = 'static';
        el.style.top = 'auto';
    }
});
