let btn_like = null;
let btn_unlike = null;
let form = null;
let nb_like_text = null;

function startup() {
    btn_like = document.getElementById('btn-like');
    btn_unlike = document.getElementById('btn-unlike');
    form = document.getElementById('comment-form');
    nb_like_text = document.getElementById('nb_like');

    if (btn_like) {
        btn_like.addEventListener('click', addLike);
    }
    
    if (btn_unlike) {
        btn_unlike.addEventListener('click', removeLike);
    }

    if (form) {
        form.addEventListener('submit', e => {
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

        }, false);
    }   
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
                        console.log(nb_like_text.textContent);
                        console.log(+nb_like_text.textContent);
                        nb_like_text.textContent = +nb_like_text.textContent + 1;
                    }
                    btn_like.removeEventListener('click', addLike);
                    startup();
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
                    startup();
                }
            }
        };
        XHR.open('POST', '../Controller/like.php', true);
        XHR.send(likeData);
    }
}

function firstLoad() {
    window.addEventListener("load", startup, false);
}

window.addEventListener("DOMContentLoaded", firstLoad, false);