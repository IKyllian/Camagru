import {create_event_listener, load_page} from "./event.js";
import {addListeners} from "./filters_actions.js";

load_page(() => {
	let streaming = false;
	let video = document.getElementById("video");
	let canvas = document.getElementById("canvas");
	let photo = document.getElementById("photo");
	let save = document.getElementById("save-post");
	let delete_pict = document.getElementById("delete-post");
	let form_file_upload = document.getElementById("form-file");
	let remove_file = document.getElementById("remove-file");
	let shoot_btn = document.getElementById("btn-shoot");
	let btn_cam = document.getElementById('btn-cam');
	let filter_dom_array = document.getElementsByClassName('filter-btn');
	let delete_icon = document.getElementById('notif-icon-delete');
	let width = canvas.width;
	let height = canvas.height;

	let filter_width = canvas.width - 20;
	let filter_height = canvas.height - 20;
	let filters = [];
	let img_src = null;
	let localstream = null;
	let cam_was_activated = false;
	let file_uploaded = false;

	function reset_settings() {
		filters = [];
		clearphoto();
		active_shoot_btn(false);
		change_remove_file_display('none');
		change_display_save_container('none');
		disable_filter_border();
		delete_all_canvas_filter();
		file_uploaded = false;
		if (cam_was_activated) {
			navigator.mediaDevices.getUserMedia({ video: true, audio: false })
			.then((stream) => {
				video.srcObject = stream;
				video.play();
				localstream = stream;
				enable_cam_display(true);
			})
			.catch((err) => {
				console.error(`An error occurred: ${err}`);
			});
		} else {
			enable_cam_display(false);
		}
	}
    
    create_event_listener(delete_icon, 'click', () => {
        let parent = document.getElementById('notif-wrapper');
        if (parent)
            parent.style.display = "none";
    });

	clearphoto();
	window.addEventListener('resize', () => {
		let canvas = document.getElementById("canvas");
		if (canvas) {
			width = canvas.width;
			height = canvas.height;
		}
	})
	create_event_listener(btn_cam, 'click', change_cam_status);
	create_event_listener(form_file_upload, 'change', on_file_change);
	create_event_listener(remove_file, 'click', (e) => {
		remove_uploaded_file(e);
	});
	create_event_listener(video, 'canplay', init_video);
	create_event_listener(shoot_btn, 'click', (e) => {
		e.preventDefault();
		takepicture();
		change_display_save_container("flex");
	});
	create_event_listener(save, 'click', () => {
		savePict();
		reset_settings();
	});
	create_event_listener(delete_pict, 'click', () => {
		reset_settings();
	});

	for (const filter of filter_dom_array) {
		create_event_listener(filter, 'click', on_filter_click);
	}

	function enable_cam_display(status) {
		if (status) {
			video.style.display = "block";
			photo.style.display = "none";
		} else {
			video.style.display = "none";
			photo.style.display = "block";
		}
	}

	function change_remove_file_display(value) {
		let label = document.getElementById('input-label');
		if (value == 'none') {
			if (label)
				label.style.display = 'flex';
		} else {
			if (label)
			label.style.display = 'none';
		}
		if (remove_file)
			remove_file.style.display = value;
	}

	function active_shoot_btn(status) {
		if (shoot_btn) {
			if (status) {
				shoot_btn.removeAttribute('disabled');
				shoot_btn.style.cursor = "pointer";
				shoot_btn.style.opacity = 1;
			}
			else {
				shoot_btn.setAttribute('disabled', '');
				shoot_btn.style.cursor = "default";
				shoot_btn.style.opacity = 0.5;
			}		
		}
	}

	function disable_filter_border() {
		let elmt = document.getElementsByClassName('filter-btn');
		for (const item of elmt) {
			if (item.style.border )
				item.style.border = null;
		}
	}

	function on_filter_click(e) {
		const target_element = e.target;
		const elem_path = target_element.attributes['src'].value;
		const filter_id = target_element.attributes['id'].value;
		if (!filters.find(e => e.path === elem_path)) {
			add_canvas_filter(target_element);
			let new_obj = {
				path: elem_path,
				offsetTop: 0,
				offsetLeft: 0,
				width: width,
				height: height,
				imgId: filter_id,
				id: `canvas-${filter_id}`,
			}
			filters.push(new_obj);
			addListeners(new_obj);
			e.target.style.border = "1px solid red";
			if (localstream)
				active_shoot_btn(true);
			if (file_uploaded)
				change_display_save_container('flex');
		} else {
			filters = filters.filter(e => e.path !== elem_path);
			e.target.style.border = null; 
			if (filters.length < 1) {
				active_shoot_btn(false);
				change_display_save_container('none');
			}
			delete_canvas_filter(target_element);
		}
	}

	function disable_cam() {
		if (localstream) {
			localstream.getTracks()[0].stop();
			localstream = null;
		}
		video.srcObject = null;
		cam_was_activated = false;
		if (btn_cam)
			btn_cam.textContent = "Enable Camera";
		enable_cam_display(false);
		active_shoot_btn(false);
	}
	
	function change_cam_status() {
		if (video.srcObject === null) {
			if (navigator && navigator.mediaDevices) {
				navigator.mediaDevices.getUserMedia({ video: true, audio: false })
				.then((stream) => {
					clearphoto();
					change_remove_file_display('none');
					video.srcObject = stream;
					localstream = stream;
					video.play();
					if (btn_cam)
						btn_cam.textContent = "Disable Camera";
					cam_was_activated = true;
					enable_cam_display(true);
					if (filters.length > 0)
						active_shoot_btn(true);
				})
				.catch((err) => {
					console.error(`An error occurred: ${err}`);
				});
			} else {
				let parent = document.getElementById('notif-wrapper');
				if (parent)
					parent.style.display = "flex";
				console.log("Cam not working");
			}
		} else {
			disable_cam();
		}
	}
	
	function on_file_change(e) {
		e.preventDefault();
		const file = e.target.files[0];
		const format = file.type;
		if (format == 'image/jpeg' || format == 'image/jpg' || format == 'image/png' || format == 'image/gif') {
			const context = canvas.getContext("2d");
			if (width && height) {
				console.log(file);
				canvas.width = width;
				canvas.height = height;
				let new_img = new Image;
				new_img.onload = function() {
					context.drawImage(new_img, 0, 0, width, height);
					const data = canvas.toDataURL("image/jpeg");
					photo.setAttribute("src", data);
					img_src = data;
					file_uploaded = true;
					URL.revokeObjectURL(new_img.src);
				}
				new_img.src = URL.createObjectURL(file);
				if (filters.length > 0)
					change_display_save_container("flex");
				disable_cam();
				change_remove_file_display('flex');
				
			}
		} else 
			alert("Format not supported");
	}

	function remove_uploaded_file(e) {
		e.preventDefault();
		clearphoto();
		img_src = null;
		if (cam_was_activated)
			enable_cam_display(true);
		else
			enable_cam_display(false);
		change_remove_file_display('none');
	}
	
	function init_video() {
		if (!streaming) {
			video.setAttribute("width", width);
			video.setAttribute("height", height);
			canvas.setAttribute("width", width);
			canvas.setAttribute("height", height);
			streaming = true;
		}
	}

	function add_pict_to_container(img_data) {
		let preview_container = document.getElementById("picture-preview");
		if (preview_container) {
			const new_li = document.createElement('li');
			const new_img = document.createElement('img');
			new_img.setAttribute("src", img_data);
			new_img.setAttribute("width", '130px');
			new_img.setAttribute("height", '130px');
			new_li.appendChild(new_img);
			preview_container.appendChild(new_li);
		}
	}

	function savePict() {
		if (img_src) {
			let imgData = new FormData();
			imgData.append('img_data', img_src);
			console.log(filters);
			if (filters.length > 0) {
				imgData.append('filters', JSON.stringify(filters));
			}
			let XHR = new XMLHttpRequest();
			XHR.onreadystatechange = function () {
				if (this.readyState === 4 && this.status === 200) {
					try {
						console.log(this.responseText);
						let response_parse = JSON.parse(this.responseText);
						if (response_parse.status) {
							add_pict_to_container(response_parse.path);
						}
					} catch(e) {
						console.log("Error " + e);
					}
				}
			};
			XHR.open('POST', '../Controller/upload.php', true);
			XHR.send(imgData);
		}
		
	}
	
	function clearphoto() {
		const context = canvas.getContext("2d");
		img_src = null;
		context.fillStyle = "#AAA";
		context.fillRect(0, 0, canvas.width, canvas.height);
	
		const data = canvas.toDataURL("image/jpeg");
		photo.setAttribute("src", data);
		change_display_save_container("none");
	}

	function change_display_save_container(value) {
		let elmt = document.getElementById('btn-save-container');
		
		if (value == 'none') {
			if (shoot_btn)
				shoot_btn.style.display = 'flex';
		} else {
			if (shoot_btn)
				shoot_btn.style.display = 'none';
		}
		if (elmt)
			elmt.style.display = value;
	}

	function create_square_span(id) {
		let new_square = document.createElement('span');

		new_square.setAttribute('id', id);
		new_square.setAttribute('class', "square-resize");
		return (new_square);
	}

	function add_canvas_filter(filter_target) {
		let div_container = document.getElementById('cam-container');
		let filterId = filter_target.attributes['id'].value;

		if (div_container) {
			let div_canvas = document.createElement('div');
			let new_canvas = document.createElement('canvas');
			let div_move = document.createElement('div');

			let square1 = create_square_span('square-top-left');
			let square2 = create_square_span('square-top-right');
			let square3 = create_square_span('square-bottom-left');
			let square4 = create_square_span('square-bottom-right');

			new_canvas.width = filter_width;
			new_canvas.height = filter_height;

			div_canvas.setAttribute('id', `canvas-${filterId}`);
			div_canvas.setAttribute('class', `filter-wrapper`);
			new_canvas.setAttribute('class', 'filters-canvas');
			div_move.setAttribute('class', 'move-filter-container');

			div_canvas.appendChild(new_canvas);
			div_canvas.appendChild(div_move);
			div_canvas.appendChild(square1);
			div_canvas.appendChild(square2);
			div_canvas.appendChild(square3);
			div_canvas.appendChild(square4);

			const ctx = new_canvas.getContext("2d");
			ctx.drawImage(filter_target, 0, 0, filter_width, filter_height);

			let canvas_wrapper = document.getElementById('canvas-wrapper');
			if (canvas_wrapper) {
				canvas_wrapper.appendChild(div_canvas);
			} else {
				let new_wrapper = document.createElement('div');
				new_wrapper.setAttribute('id', `canvas-wrapper`);
				new_wrapper.appendChild(div_canvas);
				div_container.appendChild(new_wrapper);
			}
		}
	}

	function delete_canvas_filter(filter_target) {
		let filterId = filter_target.attributes['id'].value;
		let canvas_filter = document.getElementById(`canvas-${filterId}`);
		if (canvas_filter) {
			canvas_filter.remove();
		}
	}

	function delete_all_canvas_filter() {
		var dom_filters = document.getElementById('canvas-wrapper');
		if (dom_filters)
			dom_filters.remove();
	}

	function takepicture() {
		const context = canvas.getContext("2d");
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(video, 0, 0, width, height);
			const data = canvas.toDataURL("image/jpeg");
			photo.setAttribute("src", data);
			img_src = data;
			if (filters.length > 0)
				change_display_save_container("flex");
			video.srcObject = null;
			enable_cam_display(false);
		} else {
			clearphoto();
		}
	}
});