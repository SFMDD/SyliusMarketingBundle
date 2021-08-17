document.addEventListener("DOMContentLoaded", function() {
    setIntervalX(randomNotification, 15000, 5);
});

function setIntervalX(callback, delay, repetitions) {
    var x = 0;
    var intervalID = window.setInterval(function () {
        callback();
        if (++x === repetitions) {
            window.clearInterval(intervalID);
        }
    }, delay);
}
function randomNotification() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            sendNotification(JSON.parse(xhttp.responseText));
        }
    };
    xhttp.open("GET", document.getElementById('notification-url').dataset.url, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.send();
}
function sendNotification(msg){
    if(msg.error) {
        console.log(msg.exception);
        return;
    }

    let element = document.getElementById('notify');
    if(element) element.remove();

    if(msg.notification.template !== '') {
        let div = document.createElement('div');
        div.innerHTML = msg.notification.template;
        document.getElementsByTagName('body')[0].appendChild(div);
        if(msg.notification.template.includes('trustpilot-notification') && window.Trustpilot) {
            window.Trustpilot.loadFromElement(document.getElementById('trustpilot-notification'));
        }
    }
}
