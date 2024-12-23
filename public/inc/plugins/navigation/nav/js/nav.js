const hambugerToggler = document.querySelector('.navburger');
const navLinksContainer = document.querySelector('.navlinks-container');

const togglerNav = () => {
  hambugerToggler.classList.toggle('open');

  const ariaToggler = hambugerToggler.getAttribute('aria-expanded') === "true" ? "false" : "true" ;

  hambugerToggler.setAttribute('aria-expanded', ariaToggler);

  navLinksContainer.classList.toggle('open');
}

hambugerToggler.addEventListener('click', togglerNav);

new ResizeObserver(entries => {
  if(entries[0].contentRect.width <= 900){

    navLinksContainer.style.transition = "transform 0.3s ease-out"

  }else{

    navLinksContainer.style.transition = "none"

  }
}).observe(document.body);