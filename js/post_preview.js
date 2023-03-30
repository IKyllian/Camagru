let form = null;

function startup() {
    // let forms = document.getElementsByClassName('comment-form');
    // let i = 0;
    // for (form of forms) {
    //     form.addEventListener('submit', e => {
    //         e.preventDefault();
    //         console.log("AZEAZEA: ", i);
    //         console.log(e);

    //         const formField = e.target.elements;

    //         const comment = formField.namedItem("comment").value;
    //         const post_id = formField.namedItem("post-id").value;

    //         console.log(post_id);

    //         let commentData = new FormData();
    //         commentData.append('comment', comment);
    //         commentData.append('post_id', post_id);
    //         let XHR = new XMLHttpRequest();
    //         XHR.onreadystatechange = function () {
    //             if (this.readyState === 4 && this.status === 200) {
    //                 console.log(this.responseText); }
    //             };
    //         XHR.open('POST', '../Controller/add_comment.php', true);
    //         XHR.send(commentData);

    //         i++;
    //     }, false);
    // }
    // document.getElementById('comment-form').addEventListener('submit', e => {
    //     e.preventDefault();
    //     console.log("AZEAZEA");
    // }, false);
}

window.addEventListener("load", startup, false);

