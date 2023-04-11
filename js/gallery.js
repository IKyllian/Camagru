import {create_event_listener, load_page} from "./event.js";

load_page(() => {
    // let buttons_array = document.getElementsByClassName('delete-btn');
    // console.log(buttons_array);
    // if (buttons_array.length > 0) {
    //     for (const button of buttons_array) {
    //         button.addEventListener('click', () => {
    //             let post_id = button.getAttribute('post-id');
    //             if (post_id) {
    //                 let postData = new FormData();
    //                 postData.append('post_id', +post_id);
    //                 let XHR = new XMLHttpRequest();
    //                 XHR.onreadystatechange = function () {
    //                     if (this.readyState === 4 && this.status === 200) {
    //                         if (this.responseText === 'success') {
    //                             let elmt = document.getElementById(`post-${post_id}`);
    //                             if (elmt) {
    //                                 elmt.remove();
    //                             }
    //                         }
    //                         console.log(this.responseText);
    //                     }
    //                 };
    //                 XHR.open('POST', '../Controller/delete_post.php', true);
    //                 XHR.send(postData);
    //             }
    //         })
    //     }
    // }
});