export function addListeners(filterObj) {
    let gMouseDownOffsetX = 0;
    let gMouseDownOffsetY = 0;

    const minSize = 60;

    let mouseStartX, mouseStartY, startWidth, startHeight, originalX, originalY;

    let filter_parent = document.getElementById(filterObj.id);
    let move_elmt = filter_parent.querySelector('.move-filter-container');
    let elmts_square = filter_parent.querySelectorAll('.square-resize');

    let square_selected;

    for (const square of elmts_square) {
        square.addEventListener('mousedown', mouseDownResize, false);
        window.addEventListener('mouseup', mouseUpResize, false);
    }

    filter_parent.addEventListener('mouseover', move_elmt_hover, false);
    filter_parent.addEventListener('mouseout', move_elmt_out, false);

    if (move_elmt) {
        move_elmt.addEventListener('mousedown', mouseDownMove, false);
        window.addEventListener('mouseup', mouseUpMove, false);
    }

    function move_elmt_hover() {
        let div = document.getElementById(filterObj.id);
        let squares = filter_parent.querySelectorAll('.square-resize');
        for (const square of squares) {
            square.style.display = "block";
        }
        div.style.border = "1px solid blue";
    }

    function move_elmt_out() {
        let div = document.getElementById(filterObj.id);
        let squares = filter_parent.querySelectorAll('.square-resize');
        for (const square of squares) {
            square.style.display = "none";
        }
       div.style.border = "none";
    }

    function mouseUpResize() {
        window.removeEventListener('mousemove', divResize, true);
    }

    function mouseUpMove() {
        window.removeEventListener('mousemove', divMove, true);
    }

    function mouseDownResize(e) {
        let div = document.getElementById(filterObj.id);
        square_selected = e.target;
        mouseStartX = e.clientX;
        mouseStartY = e.clientY;
        startWidth = div.offsetWidth;
        startHeight = div.offsetHeight;
        originalX = div.offsetLeft;
        originalY = div.offsetTop;
        window.addEventListener('mousemove', divResize, true);
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
    
        window.addEventListener('mousemove', divMove, true);
    }

    function divResize(e) {
        let div = document.getElementById(filterObj.id);
        let canvas = filter_parent.querySelector('.filters-canvas');
        let newWidth, newHeight;

        if (square_selected) {
            if (square_selected.id === "square-bottom-right") {
                newWidth = startWidth + (e.clientX - mouseStartX);
                newHeight = startHeight + (e.clientY - mouseStartY);
            } else if (square_selected.id === "square-bottom-left") {
                newWidth = startWidth - (e.clientX - mouseStartX);
                newHeight = startHeight + (e.clientY - mouseStartY);
                if (newWidth > minSize) {
                    let newX = originalX + (e.clientX - mouseStartX);
                    div.style.left = newX + 'px';
                }
            } else if (square_selected.id === "square-top-right") {
                newWidth = startWidth + (e.clientX - mouseStartX);
                newHeight = startHeight - (e.clientY - mouseStartY);
                if (newHeight > minSize) {
                    let newY = originalY + (e.clientY - mouseStartY);
                    div.style.top = newY + 'px';
                }
            } else if (square_selected.id === "square-top-left") {
                newWidth = startWidth - (e.clientX - mouseStartX);
                newHeight = startHeight - (e.clientY - mouseStartY);
                if (newWidth > minSize) {
                    let newX = originalX + (e.clientX - mouseStartX);
                    div.style.left = newX + 'px';
                }
                if (newHeight > minSize) {
                    let newY = originalY + (e.clientY - mouseStartY);
                    div.style.top = newY + 'px';
                }
            }
        }        

        if (newWidth > minSize) {
            div.style.width = newWidth + 'px';
            canvas.width = newWidth;
            filterObj.width = newWidth;
        }
            
        if (newHeight > minSize) {
            div.style.height = newHeight + 'px';
            canvas.height = newHeight;
            filterObj.height = newHeight;
        }
        
        if (newWidth > minSize || newHeight > minSize) {
            if (!(newWidth > minSize))
                newWidth = minSize;
            if (!(newHeight > minSize))
                newHeight = minSize;
            let img_filter = document.getElementById(filterObj.imgId);
            if (img_filter) {
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img_filter, 0, 0, newWidth, newHeight);
            }
        }   
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