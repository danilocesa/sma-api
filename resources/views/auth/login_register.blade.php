@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div id="login-forms">
  <div id="login-errors">
      <p>Incorrect Username or Password</p>

  </div>
  <form id="login-form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" /> 
    <div class="login-input">
      <label for="username" >Username</label>
      <input type="text" name="username" />
    </div>
    <div class="login-input">
      <label for="password" >Password</label>
      <input type="password" name="password" />
    </div>
    <div class="login-reg-text">
      <p>New User? <span id="register-show"><u>Register Here</u></span> </p>

    </div>
    <div class="login-submit">
      <img src="{{ asset('images/login-min.png') }}" id="submit-login" />
    </div>
  </form>
</div>
<div id="register-forms" >
  <form id="register-form" name="register-forms">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" /> 
    <div class="register-input">
      <label for="username" >Username</label>
      <input type="text" name="username" placeholder="Username" tabindex="1" />
      <p class="error-message"></p>
    </div>
    <div class="register-input">
      <label for="password" >Password</label>
      <input type="password" name="password" tabindex="2" />
      <p class="error-message"></p>
    </div>
    <div class="register-input">
      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" tabindex="3" />
      <p class="error-message"></p>
    </div>
    <div class="register-input">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="halo_halo@fh.com" tabindex="4" />
      <p class="error-message"></p>
    </div>
    <div class="register-input">
      <label>Dialect Spoken</label>
      <select name="dialect" tabindex="5">
            <option value="">Please select</option>
          @foreach($allDialects as $dialects)
            <option value="{{ $dialects->dialect_id }}">{{ $dialects->dialect }}</option>
          @endforeach
      </select>
      <p class="error-message"></p>
    </div>
    <div class="register-input">
      <label>Terms & Conditions</label>
      <div class="checkbox-container">
        <input type="checkbox" value="1" id="checkboxInput" name="terms_con" tabindex="6" />
        
        <label for="checkboxInput"></label>
        <p class="error-message"></p>
      </div>

    </div>
    <div class="register-btn">
      <div class="register-cancel">
        <img src="images/cancel.png" id="register-cancel" />
      </div>
      <div class="register-submit" >
        <img src="images/register-min.png" id="submit-register" />
      </div>
    </div>
  </form>
</div>
<div id="registration-dialog">
  <img src="{{ asset('images/success_registration.png') }}" id="regSuccessDialog" />
  <img src="{{ asset('images/account_exists.png') }}" id="regExistsDialog" />
  <img src="{{ asset('images/okay_green.png') }}" id="regSuccesButton" class="regButton" />
  <img src="{{ asset('images/okay_blue.png') }}" id="regExistsButton" class="regButton" />
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/form2js.js') }}"></script>
    <script src="{{ asset('js/callAjax.js') }}"></script>
    <script src="{{ asset('js/js-form-validator.js') }}"></script>
    <script>
     window.onload = function() {

        var $registerShow = document.getElementById('register-show'),
         $registerForms = document.getElementById('register-forms'),
         $loginForms = document.getElementById('login-forms'),
         $regsiterCancel = document.getElementById('register-cancel'),
         $submitRegister = document.getElementById('submit-register'),
         $submitLogin = document.getElementById('submit-login'),
         $logo = document.getElementById('logo'),
         $regDialog = document.getElementById('registration-dialog'),
         $regSuccessDialog = document.getElementById('regSuccessDialog'),
         $regExistsDialog = document.getElementById('regExistsDialog'),
         $regSuccesButton = document.getElementById('regSuccesButton');
         $regExistsButton = document.getElementById('regExistsButton');

        /* Show registration*/
        $registerShow.addEventListener('click', function() {
             $registerForms.style.display = 'block';
             document.getElementById('register-form').reset();
             $loginForms.style.display = 'none';
             [].slice.call( document.getElementsByClassName('error-message')).forEach(function ( p ) {
                  p.innerHTML = '';
              });
             // for (var i = 0; i < myForm = document.getElementById("register-form").elements.length; i++) {
             //    formName[i] = myForm = document.getElementById("register-form").elements[i].name;
             //  }
        }, false);

        /* Cancel registration */
        $regsiterCancel.addEventListener('click', function() {
             $registerForms.style.display = 'none';
             $loginForms.style.display = 'block';
             document.getElementById('login-form').reset();
        }, false); 

        /* Okay Registration Success */
        $regSuccesButton.addEventListener('click', function() {
             $regDialog.style.display = 'none';
             $loginForms.style.display = 'block';
             document.getElementById('login-form').reset();
             $logo.style.display = 'block';
        }, false); 

        /* Account Exists */
        $regExistsButton.addEventListener('click', function() {
             $regDialog.style.display = 'none';
             $registerForms.style.display = 'block';
             $logo.style.display = 'block';
        }, false); 

        /* On click submit register */
        $submitRegister.addEventListener('click', function() {
          var $formData = form2js('register-forms','.',true);
          callAjax("{{ url('register') }}", "POST" ,$formData,function (result) {
            console.log(result);
             if(result == 'success'){
              $logo.style.display = 'none';
              $registerForms.style.display = 'none';
              $regDialog.style.display = 'block';
              $regSuccessDialog.style.display = 'block';
              $regSuccesButton.style.display = 'block';
              $regExistsDialog.style.display = 'none';
              $regExistsButton.style.display = 'none';
             }
             else{  //Errors
              /** Display Already Exists **/
              if(result == 'exists'){
                $logo.style.display = 'none';
                $registerForms.style.display = 'none';
                $regDialog.style.display = 'block';
                $regExistsDialog.style.display = 'block';
                $regExistsButton.style.display = 'block';
                $regSuccessDialog.style.display = 'none';
                 $regSuccesButton.style.display = 'none';
              }
              /** Display Validation Error **/
              var myForm = document.getElementById("register-form"),
                  formName = [],
                  keyName = [],
                  errMessage = [],
                  objectCounter = 0;
              /** Array for list of form name **/    
              for (var i = 0; i < myForm.elements.length; i++) {
                formName[i] = myForm.elements[i].name;
              }
              /** Error Message and Input Notification **/
              for (var a = 0; a < formName.length; a++) {
                var $checkDualInput = (document.getElementsByName(formName[a])[1]) ? document.getElementsByName(formName[a])[1] : document.getElementsByName(formName[a])[0];
                Object.keys(result).forEach(function(key) {                
                  if(key == formName[a]){
                    if(formName[a] == 'terms_con'){
                      $checkDualInput.nextElementSibling.nextElementSibling.innerHTML = result['terms_con']; 
                      $checkDualInput.nextElementSibling.style.border = '5px solid red';
                     }  
                    else{
                      $checkDualInput.nextElementSibling.innerHTML = result[key]; 
                      $checkDualInput.style.border = '5px solid red';
                    }
                  }
                  else{
                    if(formName[a] != '_token')
                      if(formName[a] == 'terms_con'){
                        $checkDualInput.nextElementSibling.nextElementSibling.innerHTML = ''; 
                        $checkDualInput.nextElementSibling.style.border = '5px solid black';
                      }else{
                        $checkDualInput.nextElementSibling.innerHTML = ''; 
                        $checkDualInput.style.border = '5px solid black';
                      }
                  }     
                });
              }
                
             }
            

          });
        }, false); 

        /* On click submit login */
        $submitLogin.addEventListener('click', function() {
          var $formData = form2js('login-forms','.',true);
          callAjax("{{ url('login') }}", "POST" ,$formData,function(result){
           if(result != 'success'){
               var elements = document.querySelectorAll('.login-input');
              Array.prototype.forEach.call(elements, function(el, i){
                el.getElementsByTagName('input')[0].style.border = '5px solid red';
              });
              document.getElementById('login-errors').style.display ="block";
              console.log(result);
            }
            else{
              location.reload();
            }

          });
        }, false); 




      };
    </script>
@endpush