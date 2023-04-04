import {load_page, create_event_listener} from "./event.js";

load_page(() => {
    let form = document.getElementById('email-change');

    create_event_listener(form, 'submit', submit_form);

    function submit_form(e) {
        e.preventDefault();

        const formField = e.target.elements;
        const input_value = formField.namedItem("email").value;
        let commentData = new FormData();
        commentData.append('email', input_value);
        let XHR = new XMLHttpRequest();
        XHR.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                console.log(this.responseText); }
            };
        XHR.open('POST', '../Controller/change_email.php', true);
        XHR.send(commentData);
    }
})