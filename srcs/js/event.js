export function create_event_listener(element, event, callback) {
    if (element) {
        element.addEventListener(event, callback);
    }
}

export function load_page(callback) {
    window.addEventListener("DOMContentLoaded", () => {
        window.addEventListener("load", callback, false);
    }, false);
}