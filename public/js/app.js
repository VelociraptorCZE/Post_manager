/** Post manager
 *  Copyright (C) Simon Raichl 2018
 *  MIT License
 *  Use this as you want, share it as you want, do basically whatever you want with this :)
 */

// This JS is ugly, because I don't like myself and I tried to make this app as much compatible with older browsers as I could.

window.onload = function(){
    function checkVisibility(target, button) {
        if (target.style.display === "none"){
            target.style.display = "block";
            button.innerHTML = button.innerHTML.replace("Open", "Close");
        }
        else{
            target.style.display = "none";
            button.innerHTML = button.innerHTML.replace("Close", "Open");
        }
    }

    function addEvents(id, target){
        var toggleButton = document.getElementById(id);
        var targetElem = document.getElementById(target);
        if (toggleButton && targetElem){
            if (document.addEventListener){
                toggleButton.addEventListener("click", function(){
                    checkVisibility(targetElem, toggleButton);
                });
            }
            else{
                toggleButton.attachEvent("onclick", function(){
                    checkVisibility(targetElem, toggleButton);
                });
            }
        }
    }

    function logout(id){
        var logoutButton = document.getElementById(id);
        var loc = window.location.href.toString().slice(0, window.location.href.toString().lastIndexOf("/")) + "/public/logout.php";
        if (logoutButton){
            if (document.addEventListener){
                logoutButton.addEventListener("click", function(){
                    window.location.replace(loc);
                });
            }
            else{
                logoutButton.attachEvent("onclick", function(){
                    window.location.replace(loc);
                });
            }
        }
    }

    logout("js-logout-btn");
    addEvents("js-form-btn", "js-form");
    addEvents("js-login-box-btn", "js-login-box");
};

