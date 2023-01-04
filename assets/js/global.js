console.log('je suis global');
//Changer en dark mode
// Icon
const sunIcon = document.getElementById('sun');
const moonIcon = document.getElementById('moon');

// Theme Vars
const userTheme = localStorage.getItem('theme');
const systemTheme = window.matchMedia('(prefers-color-sheme: dark').matches;

// Icon Toggle
const iconToggle = () => {
    moonIcon.classList.toggle('hidden');
    sunIcon.classList.toggle('hidden');
};

// Initial theme check
const themeCheck = () => {
    if (userTheme === 'dark' || ( !userTheme && systemTheme )){
        document.documentElement.classList.add('dark')
        moonIcon.classList.add('hidden')
        return;
    }
    sunIcon.classList.add('hidden');
}

// Manual theme switch
const themeSwitch = () => {
  if (document.documentElement.classList.contains('dark')) {
      document.documentElement.classList.remove('dark')
      localStorage.setItem('theme', 'light')
      iconToggle();
      return;
  }
  document.documentElement.classList.add('dark');
  localStorage.setItem('theme', 'dark');
  iconToggle();
}

// Call theme switch on click buttons
sunIcon?.addEventListener('click',() => {
    themeSwitch()
});
moonIcon?.addEventListener('click', () => {
    themeSwitch();
});

// Invoke theme check on initial load
themeCheck();

// Closed alert component
let closedAlert = document.getElementById('closed')
let alertComponent = document.querySelector('.alertComponent')

closedAlert?.addEventListener('click',() => {
    alertComponent.classList.add('hidden','ease-in-out', 'transition','duration-300', 'delay-700');
});

if (alertComponent) {
    setTimeout(() => {
        alertComponent.classList.add('hidden','ease-in-out', 'transition','duration-300','delay-700')
    }, 3500)
}

// Show and hide Modal
let modalEvent = document.getElementById('modalEvent');
let cancelButton = document.querySelector('.cancel');

//Fermeture de la modal
cancelButton?.addEventListener('click', () => {
    modalEvent.classList.add('hidden');
});

//Ouverture du modal + Transfer de donnée utilisateur
let titleModal = document.querySelector('.titleModal');
let uidModal = document.getElementById('uid');
let uemailModal = document.getElementById('uemail');
document.getElementsByName('user').forEach((u) =>{
    u.addEventListener('click', () => {
        // Affichage de la modal
        modalEvent.classList.remove('hidden');
        titleModal.innerHTML = u.dataset.firstname + ' ' + u.dataset.lastname;
        uidModal.value = u.dataset.uid;
        uemailModal.value  = u.dataset.email;
    })
});

// Verifier si le champ comment n'est pas vide
let subimitButton = document.querySelector('.submited');
let textarea = document.getElementById('feedback_comment');
subimitButton?.addEventListener('click', (e) => {
    if( textarea.value === '' ){
        e.preventDefault()
        let errorMessage = document.getElementById('errorMessage')
        errorMessage.innerHTML = 'Ce champs ne doit pas être vide'
    }
});


let btnFeedSend = document.getElementById('btn-feed-send');
let btnFeedReceived = document.getElementById('btn-feed-received');
btnFeedReceived?.addEventListener('click', () => {
    let cardFeddListStateReceived = document.getElementById('card-feed-list-received');
    let cardFeddListStateSend = document.getElementById('card-feed-list-send');
    let underProgressBar = document.querySelectorAll('.underProgresBar-received');
    let upperProgressBar = document.querySelectorAll('.upperProgressBar-received');
    cardFeddListStateReceived.classList.toggle('hidden');
    underProgressBar.forEach((e) => {
        e.classList.add('bg-green-200');
    });
    upperProgressBar.forEach((u) => {
        u.classList.add( 'bg-green-300', 'text-green-500');
    });
    cardFeddListStateSend.classList.add('hidden')
});

btnFeedSend?.addEventListener('click', () => {
    let cardFeddListStateReceived = document.getElementById('card-feed-list-received');
    let cardFeddListStateSend = document.getElementById('card-feed-list-send');
    let underProgressBar = document.querySelectorAll('.underProgresBar-send');
    let upperProgressBar = document.querySelectorAll('.upperProgressBar-send');
    cardFeddListStateSend.classList.toggle('hidden');
    underProgressBar.forEach((e) => {
        e.classList.add('bg-violet-200');
    });
    upperProgressBar.forEach((u) => {
        u.classList.add( 'bg-violet-300', 'text-violet-500');
    });
    cardFeddListStateReceived.classList.add('hidden')
});

