<?php

namespace View\Auth;

class Login {


    public function render() {
        return '
        <div class="column">
        </div>
        <div class="column">
            <div id="loginContainer">
                <div id="loginArea">
                <p class="loginTitle" id="">Login</p>
                    <form id="loginForm" method="post" > 
                        <p id=""></p>
                        <hr>
                        <div>
                            <label for=""></label>
                            <input type="text" id="" name="" value="" placeholder="Username"/>
                        </div>
                    
                        <div>
                            <label for=""></label>
                            <input type="password" id="" name="" placeholder="Password"/>
                        </div>
                        <div>
                            <label for="">Keep me logged in  :</label>
                            <input type="checkbox" id="" name="" />
                        </div>
                        <div>
                        <input id="loginSubmit" type="submit" name="" value="login" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="column">
        </div>';
    }
}