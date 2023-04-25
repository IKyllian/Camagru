import {create_event_listener, load_page} from "./event.js";

load_page(() => {
    let delete_icon = document.getElementById('notif-delete');
    
    create_event_listener(delete_icon, 'click', () => {
        let parent = document.getElementById('notif-wrapper');
        if (parent)
            parent.remove();
    });
});