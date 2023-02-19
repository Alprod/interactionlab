import {switchColors} from "./switchCaseRemoveStarColors";

let yellow = 'text-yellow-300';
let yellow10 = 'text-yellow-300/10';
let yellow50 = 'text-yellow-300/50';

let dataGradesProfile = document.querySelectorAll('#rate_profil_star');

dataGradesProfile.forEach((prof) => {
  let targetDataCard = prof.dataset.grades
  let allProfilClass = prof.querySelectorAll(".profil_page")
  switchColors(allProfilClass, targetDataCard, yellow, yellow10, yellow50)
  
})
const toggleClassSidebar = (idelmt, classElemt) => {
  idelmt.addEventListener('click', ()=>{
    let list = document.querySelector(classElemt)
    list.classList.toggle('hidden')
  })
}

const button_sidebar_send = document.getElementById('dropdown_button_sidebar_feedSend')
const button_sidebar_received = document.getElementById('dropdown_button_sidebar_feedReceived')

toggleClassSidebar(button_sidebar_send, '.feedSendList')
toggleClassSidebar(button_sidebar_received, '.feedReceivedList')

const button_sidebar_send_all = document.getElementById('all_send_click')
const button_sidebar_send_15d = document.getElementById('send_15d_click')
const button_sidebar_send_30d = document.getElementById('send_30d_click')
const button_recap_profile = document.getElementById('button_recap')

let block_recap = document.querySelector('#block_recap')

let listAll = document.querySelector('#all_feedSend')
let list15 = document.querySelector('#days_send_15')
let list30 = document.querySelector('#days_send_30')

const button_sidebar_receive_all = document.getElementById('all_receive_click')
const button_sidebar_receive_15d = document.getElementById('receive_15d_click')
const button_sidebar_receive_30d = document.getElementById('receive_30d_click')

let receiAll = document.querySelector('#all_feedReceived')
let recei15 = document.querySelector('#days_received_15')
let recei30 = document.querySelector('#days_received_30')

const removeHidden = (button, block, elAdd, elRmove) =>{
  button.addEventListener('click', () =>{
    block.classList.add('hidden')
    elRmove.classList.remove('hidden')
    elAdd.forEach((e) => {
      e.classList.add('hidden')
    })
  })
}

let elAdd_1 = [list15, list30, receiAll, recei15, recei30]
removeHidden(button_sidebar_send_all, block_recap, elAdd_1, listAll )

let elAdd_2 = [recei15, recei30, listAll, list15, list30]
removeHidden(button_sidebar_receive_all, block_recap, elAdd_2, receiAll)

let elAdd_3 = [listAll, list30, receiAll, recei15, recei30]
removeHidden(button_sidebar_send_15d, block_recap, elAdd_3, list15)


let elAdd_4 = [receiAll, recei30, listAll, list15, list30]
removeHidden(button_sidebar_receive_15d, block_recap, elAdd_4, recei15)

let elAdd_5 = [listAll, list15, receiAll, recei15, recei30]
removeHidden(button_sidebar_send_30d, block_recap, elAdd_5, list30)

let elAdd_6 = [listAll, list15,list30, receiAll, recei15]
removeHidden(button_sidebar_receive_30d, block_recap, elAdd_6, recei30)


let elAdd_7 = [listAll, list15,list30, receiAll, recei15, recei30]
button_recap_profile.addEventListener('click', ()  =>{
  block_recap.classList.remove('hidden')
  elAdd_7.forEach((e) =>{
    e.classList.add('hidden')
  })
})

const button_close = document.getElementById('close_sidebar')
const openSideBar = document.getElementById('open_sidebar')
const sideBar = document.getElementById('sideBar')

button_close.addEventListener('click', ()=>{
  openSideBar.classList.replace('hidden','xs:hidden')
  sideBar.classList.add('hidden')
})
openSideBar.addEventListener('click', ()=>{
  sideBar.classList.remove('hidden')
  openSideBar.classList.replace('xs:hidden', 'hidden')
})

