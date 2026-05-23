function onScroll() {
  backToTopButton.classList.toggle('show',scrollY>1200)
  nav.classList.toggle("scroll", scrollY > 40)

  const navLinks = document.querySelectorAll('#main li a');
    const section = document.querySelectorAll('section,#home');
    let current='';
    section.forEach(sec =>{
        const secTop = sec.offsetTop;
        const secheight = sec.clientHeight
        if(this.scrollY + 50 >= secTop)
        {
            current= sec.getAttribute('id')
        }

    })

    navLinks.forEach(nav => {

        if(nav.classList.contains(current))
        {
            nav.classList.add('actived')
        }else{
          nav.classList.remove('actived');
        }
    // console.log(nav)

    })
    // console.log(current)
}
// obs: nav e um identificador unico


const openNav = document.querySelector(".open-menu");
  openNav.addEventListener("click", function () {
  document.body.classList.add("menu-expanded");
});

const closeNav = document.querySelector(".close-menu");
closeNav.addEventListener("click", function () {
  document.body.classList.remove("menu-expanded");
});

function closeMenu() {
  document.body.classList.remove("menu-expanded");
}






ScrollReveal({
  origin: "top",
  distance: "30px",
  duration: 700,
}).reveal(`
#home,
#home img,
#home .stats,
#services,
#services header,
#services .card,
#about,#about header,#about img,
#contacts, #contacts h2, #contacts img`);


// Função para animar a contagem
function animarContagem(elemento, valorFinal, duracao) {
    let valorAtual = 0;
    const incremento = valorFinal / (duracao / 16); // 16ms é o tempo aproximado de um frame

    const timer = setInterval(() => {
        valorAtual += incremento;
        if (valorAtual >= valorFinal) {
            clearInterval(timer);
            valorAtual = valorFinal;
        }
        elemento.textContent = Math.floor(valorAtual);
    }, 16);
}

// Configuração do ScrollReveal para os contadores
ScrollReveal({
    origin: "top",
    distance: "30px",
    duration: 700,
    afterReveal: function (el) {
      // Verifica qual contador foi revelado e inicia a animação correspondente
      if (el.id === "contador1") {
        animarContagem(el, 3500, 3000);
      } else if (el.id === "contador2") {
        animarContagem(el, 13, 3000);
      } else if (el.id === "contador3") {
        animarContagem(el, 10, 3000);
      } else if (el.id === "contador4") {
        animarContagem(el, 13, 3000);
      } else if (el.id === "contador5") {
        animarContagem(el, 5, 3000);
      } else if (el.id === "contador6") {
        animarContagem(el, 10, 3000);
      }
    },
  }).reveal("#contador1, #contador2, #contador3, #contador4, #contador5, #contador6");
