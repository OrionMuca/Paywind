<!DOCTYPE html>

<html>
<head>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>PayWind</title>
    <style>

        body{
            background-image: url(uploads/avatars/background-guest.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed; 
            text-align: center;    
        }

        .the-text{
            margin-top: 20%;
        }
        .overlay h1{
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: 500;
            font-size: 500%;
            color:aqua;
        }
        .overlay p{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-weight: 200;
            font-size:  30px;
            color: rgb(214, 214, 214);
        }

        .overlay h4{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-weight: 100;
            font-size:  20px;
            color: rgb(214, 214, 214);
        }
        
        .overlay {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
	        background: rgba(0, 0, 0, 0.7);
            background-size: cover;
        }

        .buttons{
            margin-top: 18%;
        }

        #log_in{
          text-decoration: none;
          color: aqua;
        }

        #register{
          text-decoration: none;
          color: aqua;
        }

        h5,h6{
            color: white;
        }

        #register:hover, #log_in:hover{
            color: #83F7FF;
        }
        
    </style>

</head>

<body>
    <div class="overlay">
        <div class="container">
          <div class="the-text">
            <h1>WELCOME TO <span>PAYWIND</span></h1>
            <p>The perfect place for every business!!</p>
            <h4>Automated system for calculating salaries</h4>
           </div>
        </div>


        <div class="footer">
            <div class="buttons">
                <h5>I'm new here <a id="register" href="{{route('register')}}">Register</a></h5>
                <h6>Already have an account? <a id="log_in" href="{{route('login')}}"> Log In</a></h6>
            </div>
          </div>
    
      </div>
 


</body>
</html>