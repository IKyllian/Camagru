import {create_event_listener, load_page} from "./event.js";

load_page(() => {
    let btn_like = null;
    let btn_unlike = null;
    let form = document.getElementById('comment-form');
    let nb_like_text = document.getElementById('nb_like');
    let delete_btn = document.getElementById('delete-btn');

    create_event_listener(form, 'submit', submit_comment);
    create_event_listener(delete_btn, 'click', delete_post)
    like_events();

    function delete_post() {
        let post_id = delete_btn.getAttribute('post-id');
        if (post_id) {
            let postData = new FormData();
            postData.append('post_id', +post_id);
            let XHR = new XMLHttpRequest();
            XHR.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    try {
                        console.log(this.responseText);
						let response_parse = JSON.parse(this.responseText);
						if (response_parse.status) {
                            window.location.replace(response_parse.location);
                        }
					} catch(e) {
						console.log("Error " + e);
					}
                }
            };
            XHR.open('POST', '../Controller/delete_post.php', true);
            XHR.send(postData);
        }
    }
    
    function like_events() {
        btn_like = document.getElementById('btn-like');
        btn_unlike = document.getElementById('btn-unlike');
    
        create_event_listener(btn_like, 'click', addLike);
        create_event_listener(btn_unlike, 'click', removeLike);
    }
    
    function submit_comment(e) {
        e.preventDefault();
        const formField = e.target.elements;
        const comment = formField.namedItem("comment") ? formField.namedItem("comment").value : null;
        const post_id = formField.namedItem("post-id") ? formField.namedItem("post-id").value : null;
        let comment_input = formField.namedItem("comment");
        let send_button = document.getElementById('send-button');
        if (comment && post_id && comment_input && send_button) {
            comment_input.disabled = "true";
            send_button.disabled = "true";
            let commentData = new FormData();
            commentData.append('comment', comment);
            commentData.append('post_id', post_id);
            let XHR = new XMLHttpRequest();
            XHR.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    console.log(this.responseText);
                    try {
                        let response_parse = JSON.parse(this.responseText);
                        if (response_parse.status) {
                            add_comment_to_dom(response_parse.comment);
                            comment_input.value = "";
                            comment_input.disabled = "";
                            send_button.disabled = "";
                        }
                    } catch(e) {
                        console.log("Error " + e);
                        comment_input.disabled = "";
                        send_button.disabled = "";
                    }
                }
            };
            XHR.open('POST', '../Controller/add_comment.php', true);
            XHR.send(commentData);
        }
    }

    function add_comment_to_dom(comment) {
        let ul_elmt = document.getElementById('comment-list');
        let new_li = document.createElement('li');
        let new_li_content = `<a href="/View/profile.php?id=${comment.user_id}" class="comment-sender">${comment.username} </a> <p class="comment-content"> ${comment.content} </p>`;
        new_li.innerHTML = new_li_content;
        if (ul_elmt) {
            ul_elmt.appendChild(new_li);
        } else {
            let wrapper = document.getElementById('comment-wrapper');
            let no_comments = document.getElementById('no-comments');
            if (no_comments)
                no_comments.remove();
            if (wrapper) {
                let new_ul = document.createElement('ul');
                new_ul.setAttribute('id', "comment-list");
                new_ul.appendChild(new_li);
                wrapper.appendChild(new_ul);
            }
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
                    try {
						let response_parse = JSON.parse(this.responseText);
						if (response_parse.status) {
							btn_like.classList.remove("far");
                            btn_like.classList.add("fas");
                            btn_like.id = 'btn-unlike';
                            if (nb_like_text) {
                                nb_like_text.textContent = +nb_like_text.textContent + 1;
                            }
                            btn_like.removeEventListener('click', addLike);
                            like_events();
                        }
					} catch(e) {
						console.log("Error " + e);
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
                    try {
						let response_parse = JSON.parse(this.responseText);
						if (response_parse.status) {
							btn_unlike.classList.remove("fas");
                            btn_unlike.classList.add("far");
                            btn_unlike.id = 'btn-like';
                            nb_like_text.textContent = +nb_like_text.textContent - 1;
                            btn_unlike.removeEventListener('click', removeLike);
                            like_events();
						}
					} catch(e) {
						console.log("Error " + e);
					}
                }
            };
            XHR.open('POST', '../Controller/like.php', true);
            XHR.send(likeData);
        }
    }
});