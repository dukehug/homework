'use strict';

//use document.querySelector to select the button element
const switcher = document.querySelector('.btn');

//event listener for the button click
switcher.addEventListener('click', function() {
    //toggle the light-theme class on the body element
    document.body.classList.toggle('light-theme');
    //toggle the dark-theme class on the body element
    document.body.classList.toggle('dark-theme');

    //conditional statement to check if the body has the light-theme class
    const className = document.body.className;
    if(className == "light-theme") {
        this.textContent = 'Dark';
    } else {
        this.textContent = 'Light';
    }

    //get the current class name of the body element
    console.log('Current class name: ' + document.body.className);

    //printout the current time and date of the theme switch
    console.log('Theme switched at: ' + new Date().toLocaleString());

});