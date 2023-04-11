import {create_event_listener, load_page} from "./event.js";

load_page(() => {
	let streaming = false;
	let video = document.getElementById("video");
	let canvas = document.getElementById("canvas");
	let photo = document.getElementById("photo");
	let save = document.getElementById("save");
	let delete_pict = document.getElementById("delete");
	let form_file_upload = document.getElementById("form-file");
	let remove_file = document.getElementById("remove-file");
	let shoot_btn = document.getElementById("btn-shoot");
	let btn_cam = document.getElementById('btn-cam');
	let filter_array = document.getElementsByClassName('filter-btn');
	let width = canvas.width;
	let height = canvas.height;
	let filters = [];
	let localstream = null;
	let cam_was_activated = false;
	let file_uploaded = false;

	function reset_settings() {
		filters = [];
		clearphoto();
		change_remove_file_display('none');
		disable_filter_border();
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

	clearphoto();
	create_event_listener(btn_cam, 'click', change_cam_status);
	create_event_listener(form_file_upload, 'change', on_file_change);
	create_event_listener(remove_file, 'click', (e) => {
		remove_uploaded_file(e);
	});
	create_event_listener(video, 'canplay', init_video);
	create_event_listener(shoot_btn, 'click', (e) => {
		e.preventDefault();
		takepicture();
		change_display_save_container("block");
	});
	create_event_listener(save, 'click', () => {
		savePict(photo);
		reset_settings();
	});
	create_event_listener(delete_pict, 'click', () => {
		reset_settings();
	});

	for (const filter of filter_array) {
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
		if (remove_file)
			remove_file.style.display = value;
	}

	function active_shoot_btn(status) {
		if (shoot_btn) {
			if (status)
				shoot_btn.removeAttribute('disabled');
			else
				shoot_btn.setAttribute('disabled', '');
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
		const elem_path = e.target.attributes['src'].value
		if (!filters.find(e => e === elem_path)) {
			filters.push(elem_path);
			e.target.style.border = "1px solid red";
			if (localstream)
				active_shoot_btn(true);
			if (file_uploaded)
				change_display_save_container('block');
		} else {
			filters = filters.filter(e => e !== elem_path);
			e.target.style.border = null; 
			if (filters.length < 1) {
				active_shoot_btn(false);
				change_display_save_container('none');
			}
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
			navigator.mediaDevices.getUserMedia({ video: true, audio: false })
			.then((stream) => {
				clearphoto();
				change_remove_file_display('none');
				video.srcObject = stream;
				localstream = stream;
				video.play();
				console.log(btn_cam);
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
					URL.revokeObjectURL(new_img.src);
				}
				new_img.src =  URL.createObjectURL(file);
				if (filters.length > 0)
					change_display_save_container("block");
				disable_cam();
				change_remove_file_display('block');
				file_uploaded = true;
			}
		} else 
			alert("Format not supported");
	}

	function remove_uploaded_file(e) {
		e.preventDefault();
		clearphoto();
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

	function savePict(img) {
		let imgData = new FormData();
		imgData.append('img_data', img.src);
		imgData.append('img_id', img.id);
		if (filters.length > 0) {
			imgData.append('filters', JSON.stringify(filters));
		}
		let XHR = new XMLHttpRequest();
		XHR.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200) {
				console.log(this.responseText);
				if (this.responseText !== 'error') {
					add_pict_to_container(this.responseText);					
				}
			}
			};
		XHR.open('POST', '../Controller/upload.php', true);
		XHR.send(imgData);
	}
	
	function clearphoto() {
		const context = canvas.getContext("2d");
		context.fillStyle = "#AAA";
		context.fillRect(0, 0, canvas.width, canvas.height);
	
		const data = canvas.toDataURL("image/jpeg");
		photo.setAttribute("src", data);
		change_display_save_container("none");
	}

	function change_display_save_container(value) {
		let elmt = document.getElementById('btn-save-container');
		if (elmt)
			elmt.style.display = value;
	}
	
	function takepicture() {
		const context = canvas.getContext("2d");
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(video, 0, 0, width, height);
			const data = canvas.toDataURL("image/jpeg");
			photo.setAttribute("src", data);
			if (filters.length > 0)
				change_display_save_container("block");
			video.srcObject = null;
			enable_cam_display(false);
		} else {
			clearphoto();
		}
	}
});