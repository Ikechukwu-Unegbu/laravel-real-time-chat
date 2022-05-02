let searchBtn = document.getElementById('searchbtn');
let searchInpt = document.getElementById('searchinput')
let searchForm = document.getElementById('searchform')

searchBtn.addEventListener('click', function(e){
  let input  = searchInpt.value
  if(input != ''){
    let payload = {
      input:input
    }
    let options ={
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        // 'X-CSRF-Token': document.querySelector('input[name="_csrf"]').value
      },
      body:JSON.stringify(payload)
    }
      fetch('/chat/search', options)
    .then(response => response.json())
    .then(data => {
      console.log(data.records)
      if(data.total==0){
        //send nothing found to dom
        document.getElementById('nothing-found').style.display = 'block';
      }
      if(data.total >0){
        let target = document.getElementById('ul');
        target.innerHTML = '';
        for(let user of Object.entries(data.records.data)){
          // console.log(user[1].name)
          let html = `<li> 
                    <div class="user">
                      <p onclick="userClicked({name:'${user[1].name}',
                                                id:'${user[1].id}'})" id="${user[1].id}"><b>${user[1].name}</b></p>
                    </div>
                  </li>`;
          target.insertAdjacentHTML("afterbegin", html);
        }
        
      }

  });
   
  }
})

searchInpt.addEventListener('input', function(event){
  console.log(event.target.value)
})