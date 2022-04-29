<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/chat/index.css')}}">
    <title>Artisan Chat!</title>
  </head>
  <body>
    <!-- <h1>Hello, world!</h1> -->
    <div class="chat-container ">
      <div class="aside">
        <div class="chat-form">
          <form action="" method="">
            <div class="form-group">
              <input type="text" class="form-control">
            </div>
          </form>
        </div>
      </div>
      <div class="section">
        <div class="container chat-box-container">
          <!-- chat box -->
          <div class="chat-box ">
            <div class="chat-box-header">
              <h5>{{$user->name}}</h5>
            </div>
            <div class="">
              <ul>

              </ul>
            </div>
          </div>
          <!-- chat input -->
          <div class="chat-input">
            <div class="" id="chatinput" style="overflow:hidden; background-color: white; border-style:solid; border-color:gainsboro;"  contentEditable="true">
            </div>
            <button id="chatbtn" style="float: right;" class="btn btn-primary">Send</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
    <script>
      //listen for click on send button
      let chatBtn = document.getElementById('chatbtn');
      let chatbox = document.getElementById('chatinput');
      let searchBtn = document.getElementById('searchbtn');
      
      chatBtn.addEventListener('click', function(){
        //check if the input field is not empty
        if(chatbox.innerText !=''){
          console.log(chatbox.innerText);

        }
      });

      // live search functionality
    </script>
  </body>
</html>