import {create_event_listener, load_page} from "./event.js";

load_page(() => {
    let btn_like = null;
    let btn_unlike = null;
    let form = document.getElementById('comment-form');
    let nb_like_text = document.getElementById('nb_like');

    create_event_listener(form, 'submit', submit_comment);
    like_events();
    
    function like_events() {
        btn_like = document.getElementById('btn-like');
        btn_unlike = document.getElementById('btn-unlike');
    
        create_event_listener(btn_like, 'click', addLike);
        create_event_listener(btn_unlike, 'click', removeLike);
    }
    
    function submit_comment(e) { // Faire en sorte de ne pas pouvoir spam le bouton envoyer
        e.preventDefault();
    
        const formField = e.target.elements;
        const comment = formField.namedItem("comment").value;
        const post_id = formField.namedItem("post-id").value;
        let commentData = new FormData();
        commentData.append('comment', comment);
        commentData.append('post_id', post_id);
        let XHR = new XMLHttpRequest();
        XHR.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                console.log(this.responseText); }
            };
        XHR.open('POST', '../Controller/add_comment.php', true);
        XHR.send(commentData);
    }
    
    function addLike(e) {
        let likeData = new FormData();
        let post_id = e.target.attributes['post-id'].value;
        if (post_id) {
            likeData.append('action', "like");
            likeData.append('post_id', post_id);
            let XHR = new XMLHttpRequest();
            XHR.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    if (this.responseText == "success") {
                        btn_like.id = 'btn-unlike';
                        btn_like.textContent = 'unlike';
                        if (nb_like_text) {
                            nb_like_text.textContent = +nb_like_text.textContent + 1;
                        }
                        btn_like.removeEventListener('click', addLike);
                        like_events();
                    }
                }
            };
            XHR.open('POST', '../Controller/like.php', true);
            XHR.send(likeData);
        }
    }
    
    function removeLike(e) {
        let likeData = new FormData();
        let post_id = e.target.attributes['post-id'].value;
        if (post_id) {
            likeData.append('action', "unlike");
            likeData.append('post_id', post_id);
            let XHR = new XMLHttpRequest();
            XHR.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    if (this.responseText == "success") {
                        btn_unlike.id = 'btn-like';
                        btn_unlike.textContent = 'like';
                        nb_like_text.textContent = +nb_like_text.textContent - 1;
                        btn_unlike.removeEventListener('click', removeLike);
                        like_events();
                    }
                }
            };
            XHR.open('POST', '../Controller/like.php', true);
            XHR.send(likeData);
        }
    }
});