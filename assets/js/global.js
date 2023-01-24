import {switchColors, switchCase1} from "./switchCaseRemoveStarColors";

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
    }, 5000)
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
let submitButton = document.querySelector('.submited');
let textarea = document.getElementById('feedback_comment');
let commentHelp = document.getElementById('comment_help')

let num = 0
if(commentHelp) commentHelp.innerHTML = num +'/300 caractères max.'
textarea?.addEventListener('keyup', (e) => {
    let str = e.target.value.trim().length
    commentHelp.innerHTML = str +'/300 caractères max.'
    if(str === 300 ) {
        e.preventDefault()
        console.log(str)
    }

})


submitButton?.addEventListener('click', (e) => {
    let numLettre = textarea.value.trim().length
    let errorMessage = document.getElementById('errorMessage')
    if( textarea.value === '' ){
        e.preventDefault()
        errorMessage.innerHTML = 'Ce champs ne doit pas être vide'
    }else if(numLettre < 10){
        e.preventDefault()
        errorMessage.innerHTML = `Vous pouvez faire mieux que ça, ${numLettre} caractères il m'en faut 10 et on sera au top! `;
    }
}
);


let btnFeedSend = document.getElementById('btn-feed-send');
let btnFeedReceived = document.getElementById('btn-feed-received');
btnFeedReceived?.addEventListener('click', () => {
    let cardFeedListStateReceived = document.getElementById('card-feed-list-received');
    let cardFeedListStateSend = document.getElementById('card-feed-list-send');
    let underProgressBar = document.querySelectorAll('.underProgressBar-received');
    let upperProgressBar = document.querySelectorAll('.upperProgressBar-received');
    cardFeedListStateReceived.classList.toggle('hidden');
    underProgressBar.forEach((e) => {
        e.classList.add('bg-green-200');
    });
    upperProgressBar.forEach((u) => {
        u.classList.add( 'bg-green-300', 'text-green-500');
    });
    cardFeedListStateSend.classList.add('hidden')
});

btnFeedSend?.addEventListener('click', () => {

    let cardFeedListStateReceived = document.getElementById('card-feed-list-received');
    let cardFeedListStateSend = document.getElementById('card-feed-list-send');
    let underProgressBar = document.querySelectorAll('.underProgressBar-send');
    let upperProgressBar = document.querySelectorAll('.upperProgressBar-send');

    cardFeedListStateSend.classList.toggle('hidden');

    underProgressBar.forEach((e) => {
        e.classList.add('bg-violet-200');
    });

    upperProgressBar.forEach((u) => {
        u.classList.add( 'bg-violet-300', 'text-violet-500');
    });

    cardFeedListStateReceived.classList.add('hidden')
});


let allRangeInput = document.querySelectorAll('.range');
let rangeInput = document.getElementById('feedback_grade');
let yellow = 'text-yellow-300';
let yellow10 = 'text-yellow-300/10';
let yellow50 = 'text-yellow-300/50';

if(rangeInput) rangeInput.value = 0;

rangeInput?.addEventListener('input', (event) =>{
    let rangeValue = event.target.value;
    switchColors(allRangeInput, rangeValue, yellow, yellow10, yellow50)
})

//reset datas de la modal
let modalCancelButton = document.querySelector('.cancel');
modalCancelButton?.addEventListener('click', ()=>{
    allRangeInput.forEach((rang) => {
        switchCase1(rang, yellow, yellow10, yellow50);
    })
    if(rangeInput && textarea && commentHelp) {
        rangeInput.value = 0;
        textarea.value = ''
        commentHelp.innerHTML = '0/300 caractères max.'
    }
});

//couleur des étoiles dans la page interaction
let dataGrades = document.querySelector('#rate_grade_star');
let grades = dataGrades.dataset.grades
let allNoteClass = document.querySelectorAll('.note')

switchColors(allNoteClass, grades, yellow, yellow10, yellow50);

let dataCardGrades = document.querySelectorAll('.cardStart')

dataCardGrades.forEach((card) => {
    let targetDataCard = card.dataset.gradesCard
    let allCardClass = card.querySelectorAll(".starCard")
    switchColors(allCardClass, targetDataCard, yellow, yellow10, yellow50)
})