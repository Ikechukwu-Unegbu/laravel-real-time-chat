let chatinput = document.getElementById('chatinput');
let chatdisplay = document.getElementById('chatdisplay-ul')
let peer = document.getElementById('peer')

function userClicked(data){
  peer.innerText = '';
  peer.innerText = data.name;
  console.log(data)
  chatinput.setAttribute('data-key', data.id)
}

//console.log(chattingWith)