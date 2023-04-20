export function addListeners(filterObj) {
    let gMouseDownOffsetX = 0;
    let gMouseDownOffsetY = 0;

    let startX, startY, startWidth, startHeight;

    let filter_parent = document.getElementById(filterObj.id);
    let move_elmt = filter_parent.querySelector('.move-filter-container');
    // let right_resize = filter_parent.querySelector('#right-resize');
    let elmts_square = filter_parent.querySelectorAll('.square-resize');

    for (const square of elmts_square) {
        square.addEventListener('mousedown', mouseDownResize, false);
        window.addEventListener('mouseup', mouseUpResize, false);
    }

    
    filter_parent.addEventListener('mouseover', move_elmt_hover, false);
    filter_parent.addEventListener('mouseout', move_elmt_out, false);

    if (move_elmt) {
        move_elmt.addEventListener('mousedown', mouseDownMove, false);
        move_elmt.addEventListener('mouseup', mouseUpMove, false);
    }

    function move_elmt_hover() {
        let div = document.getElementById(filterObj.id);
       div.style.border = "1px solid blue";
    }

    function move_elmt_out() {
        let div = document.getElementById(filterObj.id);
       div.style.border = "none";
    }

    function mouseUpResize(e) {
        e.target.removeEventListener('mousemove', divResize, true);
    }

    function mouseUpMove() {
        move_elmt.removeEventListener('mousemove', divMove, true);
    }

    function mouseDownResize(e) {
        let div = document.getElementById(filterObj.id);
        startX = e.clientX;
        startY = e.clientY;
        startWidth = div.offsetWidth;
        startHeight = div.offsetHeight;
        e.target.addEventListener('mousemove', divResize, true);
    }
    
    function mouseDownMove(e) {
        let gMouseDownX = e.clientX;
        let gMouseDownY = e.clientY;
    
        var div = document.getElementById(filterObj.id);
    
        //The following block gets the X offset (the difference between where it starts and where it was clicked)
        let leftPart = "";
        if(!div.style.left)
            leftPart+="0px";
        else
            leftPart = div.style.left;
        let leftPos = leftPart.indexOf("px");
        let leftNumString = leftPart.slice(0, leftPos);
        gMouseDownOffsetX = gMouseDownX - parseInt(leftNumString,10);
    
        //The following block gets the Y offset (the difference between where it starts and where it was clicked)
        let topPart = "";
        if(!div.style.top)
            topPart+="0px";
        else
            topPart = div.style.top;
        let topPos = topPart.indexOf("px");
        let topNumString = topPart.slice(0, topPos);
        gMouseDownOffsetY = gMouseDownY - parseInt(topNumString,10);
    
        move_elmt.addEventListener('mousemove', divMove, true);
    }

    function divResize(e) {
        let div = document.getElementById(filterObj.id);
        let canvas = filter_parent.querySelector('.filters-canvas');

        let newX = (startWidth + e.clientX - startX);
        let newY = (startHeight + e.clientY - startY);

        div.style.width = newX + 'px';
        div.style.height = newY + 'px';
        canvas.width = newX;
        canvas.height = newY;

        let img_filter = document.getElementById(filterObj.imgId);
        if (img_filter) {
            const ctx = canvas.getContext("2d");
            ctx.drawImage(img_filter, 0, 0, newX, newY);
        }

        filterObj.width = newX;
        filterObj.height = newY;
    }

    function divMove(e){
        var div = document.getElementById(filterObj.id);
        let topAmount = e.clientY - gMouseDownOffsetY;
        div.style.top = topAmount + 'px';
        let leftAmount = e.clientX - gMouseDownOffsetX;
        div.style.left = leftAmount + 'px';
        filterObj.offsetTop = topAmount;
        filterObj.offsetLeft = leftAmount;
    }
}