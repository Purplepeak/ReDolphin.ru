function showFileSize(maxFileSize, form) {
    var input, file;

    input = form.elements.userfile;
    if (input.files[0]) {
    	if (input.files[0].size > maxFileSize * 1024 * 1024) {
    		bodyAppend("div", "Размер загружаемого файла должен быть не более " + maxFileSize + " МБ.");
    		form.reset();
    		return false;
    	}
    } else {
    	bodyAppend("div", "Вы не выбрали файл, который необходимо загрузить. Пожалуйста, повторите попытку.");
    	return false;
    }
}

// Функция выводит на экран предупреждающее сообщение

function bodyAppend(div, innerHTML) {
    var parentDiv = document.getElementById('alert-message');
    var elem = parentDiv.children[0];
    if (elem) {
        remove(elem);
    }
    var errorDiv = document.createElement(div);
    errorDiv.className = 'alert alert-danger';
    errorDiv.innerHTML = innerHTML;
    parentDiv.appendChild(errorDiv);

}

// Чтобы сообщения не плодились используем

function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}