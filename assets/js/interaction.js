import {switchColors, switchCase1} from "./switchCaseRemoveStarColors";
console.log('je suis interaction page');


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
  cardFeedListStateReceived.classList.toggle('hidden');
  cardFeedListStateSend.classList.add('hidden')
});

btnFeedSend?.addEventListener('click', () => {
  let cardFeedListStateReceived = document.getElementById('card-feed-list-received');
  let cardFeedListStateSend = document.getElementById('card-feed-list-send');
  cardFeedListStateSend.classList.toggle('hidden');
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


// etoile de la page interaction page
let dataGrades = document.querySelector('#interaction_page_rate_star');
let grades = dataGrades.dataset.grades
let allNoteClass = document.querySelectorAll('.interaction_page')
switchColors(allNoteClass, grades, yellow, yellow10, yellow50);


// etoile des cartes de la page interaction
let dataCardGrades = document.querySelectorAll('#card_start')
dataCardGrades.forEach((card) => {
  let targetDataCard = card.dataset.grades
  let allCardClass = card.querySelectorAll(".start_card")
  switchColors(allCardClass, targetDataCard, yellow, yellow10, yellow50)
})
