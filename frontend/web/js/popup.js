function openPopup(id) {
	var container = document.querySelector('#'+id);
	var popup = container.querySelector('.popup');
	var overlay = container.querySelector('.overlay_opaque');
	popup.style.display = 'block';
	overlay.style.display = 'block';
}

function closePopup(id) {
	var container = document.querySelector('#'+id);
	var popup = container.querySelector('.popup');
	var overlay = container.querySelector('.overlay_opaque');
	popup.style.display = 'none';
	overlay.style.display = 'none';
}