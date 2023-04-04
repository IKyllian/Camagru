import {create_event_listener, load_page} from "./event.js";

load_page(() => {
	let streaming = false;
	let video = document.getElementById("video");
	let canvas = document.getElementById("canvas");
	let photo = document.getElementById("photo");
	let save = document.getElementById("save");
	let form_file_upload = document.getElementById("form-file");
	let startbutton = document.getElementById("btn-shoot");
	let btn_cam = document.getElementById('btn-cam');
	let filter_array = document.getElementsByClassName('filter-btn');
	let width = canvas.width;
	let height = canvas.height;
	let filters = [];

	create_event_listener(btn_cam, 'click', activate_cam);
	create_event_listener(form_file_upload, 'change', on_file_change);
	create_event_listener(video, 'canplay', init_video);
	create_event_listener(startbutton, 'click', (e) => {
		e.preventDefault();
		takepicture();
	});
	create_event_listener(save, 'click', () => {
		savePict(photo);
	});

	for (const filter of filter_array) {
		create_event_listener(filter, 'click', on_filter_click);
	}

	function on_filter_click(e) {
		const elem_path = e.target.attributes['src'].value
		if (!filters.find(e => e === elem_path)) {
			filters.push(elem_path);
			e.target.style.border = "1px solid red";
		} else {
			filters = filters.filter(e => e !== elem_path);
			e.target.style.border = "none";
		}
		console.log(filters);
	}
	
	function activate_cam() {
		navigator.mediaDevices.getUserMedia({ video: true, audio: false })
		.then((stream) => {
			video.srcObject = stream;
			video.play();
		})
		.catch((err) => {
			console.error(`An error occurred: ${err}`);
		});
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
			}
		} else 
			alert("Format not supported");
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
				console.log(this.responseText); }
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
	}
	
	function takepicture() {
		const context = canvas.getContext("2d");
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(video, 0, 0, width, height);
	
			const data = canvas.toDataURL("image/jpeg");
			photo.setAttribute("src", data);
		} else {
			clearphoto();
		}
	}
});