import {switchColors} from "./switchCaseRemoveStarColors";

console.log('je suis profile')

let yellow = 'text-yellow-300';
let yellow10 = 'text-yellow-300/10';
let yellow50 = 'text-yellow-300/50';

let dataGradesProfile = document.querySelectorAll('#rate_profil_star');

dataGradesProfile.forEach((prof) => {
  let targetDataCard = prof.dataset.grades
  let allProfilClass = prof.querySelectorAll(".profil_page")
  switchColors(allProfilClass, targetDataCard, yellow, yellow10, yellow50)
  
})

