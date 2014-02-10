function showFileSize(maxFileSize) {
    var input, file;

    input = document.getElementById('fileinput');
    if (!input.files) {
        return true;
    } else if (!input.files[0]) {
        bodyAppend("div", "Вы не выбрали файл, который необходимо загрузить. Пожалуйста, повторите попытку.");
        return false;
    } else {
        file = input.files[0];
        if (file.size > maxFileSize * 1024 * 1024) {
            bodyAppend("div", "Размер загружаемого файла должен быть не более " + maxFileSize + " МБ.");
            document.upload_form.reset();
            return false;
        }
    }
}

// Функция выводит на экран предупреждающее сообщение

function bodyAppend(div, innerHTML) {
    var parentDiv = document.body.children[2];
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