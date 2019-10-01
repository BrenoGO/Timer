<?php

echo '
  <div>
    <div>
      <h1>Please Sign Up or Log In</h1>
    </div>
    <div>
      <h2>Sign In</h2>
      <form method="post" action="signingUp.php">
      <label for="email">
        E-mail: 
        <input type="email" name="email" id="email" />
      </label></br>
      <label for="name">
        Name: 
        <input type="text" name="name" id="name" />
      </label></br>
      <label for="password">
        Password: 
        <input type="password" name="password" id="password" />
      </label></br>
      <label for="confPassword">
        Confirm the password: 
        <input type="password" name="confPassword" id="confPassword" />
      </label></br>
      <input type="submit" value="SIGN UP" />
      </form>
    </div>
    <div>
      <h2>Login</h2>
      <form method="post" action="login.php">
      <label for="emailLogin">
        E-mail: 
        <input type="email" name="email" id="emailLogin" />
      </label></br>
      <label for="passwordLogin">
        Password: 
        <input type="password" name="password" id="passwordLogin" />
      </label></br>
      <input type="submit" value="LOG IN" />
      </form>
    </div>
  </div>
';