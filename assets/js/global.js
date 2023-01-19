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

let rangeInput = document.getElementById('feedback_grade');

rangeInput.value = 0;

rangeInput.addEventListener('input', (event) =>{

    let allRangeInput = document.querySelectorAll('.range');
    let rangeValue = event.target.value;
    let yellow = 'text-yellow-300';
    let yellow10 = 'text-yellow-300/10';
    let yellow50 = 'text-yellow-300/50';

    function switchCaseZero(item, y, y10, y50) {
        item?.forEach((i) => {
            i.classList.remove(y)
            i.classList.remove(y50)
            i.classList.add(y10)
        })
    }

    function switchCaseDecimal(item, y, y10, y50) {
        item.classList.remove(y);
        item.classList.remove(y10);
        item.classList.add(y50);
    }

    function switchCase(item, y, y10, y50) {
        item.classList.remove(y50);
        item.classList.remove(y10);
        item.classList.add(y);
    }
    function switchCaseInt10(item,item1, y, y10, y50) {
        item.classList.remove(y10)
        item.classList.add(y)
        item1.classList.remove(y50)
        item1.classList.add(y10)
    }
    function switchCaseInt50(item,item1, y, y10, y50) {
        item.classList.remove(y50);
        item.classList.add(y);
        item1.classList.remove(y50)
        item1.classList.add(y10)
    }

    console.log(rangeValue);
    switch (true) {
        case rangeValue <= 0 :
            switchCaseZero(allRangeInput, yellow, yellow10, yellow50)
            break
        case rangeValue >= 0.1 && rangeValue <= 0.8 :
            allRangeInput[0].classList.remove(yellow)
            allRangeInput[0].classList.add(yellow50)
            switchCaseZero()
            break

        case rangeValue >= 0.9 && rangeValue <= 1 :
            switchCaseInt10(allRangeInput[0], allRangeInput[1], yellow, yellow10, yellow50)
            break

        case rangeValue >= 1.1 && rangeValue <= 1.8 :
            switchCase(allRangeInput[0], yellow, yellow10, yellow50)
            switchCaseDecimal(allRangeInput[1], yellow, yellow10, yellow50)
            break
        case rangeValue >= 1.9 && rangeValue <= 2:
            switchCaseInt50(allRangeInput[1], allRangeInput[2], yellow, yellow10, yellow50)
            break;

        case rangeValue >= 2.1 && rangeValue <= 2.8 :
            switchCase(allRangeInput[0], yellow, yellow10, yellow50)
            switchCase(allRangeInput[1], yellow, yellow10, yellow50)
            switchCaseDecimal(allRangeInput[2],yellow,yellow10,yellow50)
            break;

        case rangeValue >= 2.9 && rangeValue <= 3:
            switchCaseInt50(allRangeInput[2], allRangeInput[3], yellow, yellow10, yellow50)
            break;

        case rangeValue >= 3.1 && rangeValue <= 3.8:
            switchCase(allRangeInput[0], yellow, yellow10, yellow50)
            switchCase(allRangeInput[1], yellow, yellow10, yellow50)
            switchCase(allRangeInput[2], yellow, yellow10, yellow50)
            switchCaseDecimal(allRangeInput[3],yellow,yellow10,yellow50)
            break;

        case rangeValue >= 3.9 && rangeValue <=4:
            switchCaseInt50(allRangeInput[3], allRangeInput[4], yellow, yellow10, yellow50)
            break;

        case rangeValue >= 4.1 && rangeValue <= 4.8:
            switchCase(allRangeInput[0], yellow, yellow10, yellow50)
            switchCase(allRangeInput[1], yellow, yellow10, yellow50)
            switchCase(allRangeInput[2], yellow, yellow10, yellow50)
            switchCase(allRangeInput[3], yellow, yellow10, yellow50)
            switchCaseDecimal(allRangeInput[4],yellow,yellow10,yellow50)
            break;
        case rangeValue >= 4.9:
            allRangeInput.forEach((e) => {
                e.classList.remove(yellow50);
                e.classList.add(yellow)
            })
    }
})

let modalCancelButton = document.querySelector('.cancel');
modalCancelButton.addEventListener('click', ()=>{
    rangeInput.value = 0;
});


