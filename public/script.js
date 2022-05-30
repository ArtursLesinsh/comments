const form = document.getElementById('comments_form');
const popup = document.querySelector('.popup');
const form_update = document.getElementById('comments_update_form');
const comment_block = document.querySelector('.comments');
const comment_template = comment_block.querySelector('.template');

const background_container = document.querySelector('.background_container');
const image_template = background_container.querySelector('.template');

/* здесь мы обьединили 2 цикла, которые выполняют одни и те-же действия, но на разных веб страниц */
xhttp.get('api.php?object=batch&action=getAll', function (response) {
    for (let image of response.images) {
        addBackgroundImage('endpoint.php?name=png&id=' + image.id);
    }
    for (let comment of response.comments) {
        addComment(comment.id, comment.author, comment.message);
    }
});

form.onsubmit = function (event) {
    event.preventDefault();
    submitForm(this);
};
/* Форма, что бы обновить данные для update (popup) формы */
form_update.onsubmit = function (event) {
    event.preventDefault();

    xhttp.postForm(form_update, function (response) {
        popup.style.display = 'none';
/* Здесь при отправке обнавленных данных, появляется стиль 'none' и popup исчезает */

        const update_comment = document.querySelector('[data-id="' + response.id + '"]');//
        update_comment.querySelector('.message').textContent = response.comment.message;
        update_comment.querySelector('.author').textContent = response.comment.author;
/* Здесь при отправке обнавленных данных, обновляется содержимое HTML документа 
data-id="' -это то, что есть у каждого коментария в разделе "Elements" и по нему мы обновляемя правильный коментарий
[] отсеиваем все элементы по атрибутам */
    });
}

function submitForm (form) {
    xhttp.postForm(form, function (response) {
        addComment(response.id, response.author, response.message);
    });
}

function addComment(id, author, message) {
    const new_comment = comment_template.cloneNode(true);
    new_comment.classList.remove('template');
    new_comment.querySelector('.message').textContent = message;
    new_comment.querySelector('.author').textContent = author;
    new_comment.dataset.id = id;

    new_comment.querySelector('.delete').onclick = function (event) {
        const data = new FormData();
        data.set('id', id);

        xhttp.post('api.php?object=comment&action=delete', data, function (response) {
            new_comment.remove();
        });
    };

    new_comment.querySelector('.edit').onclick = function (event) {
        const data = new FormData();
        data.set('id', id);

        xhttp.post('api.php?object=comment&action=get', data, function (response) {
            popup.style.display = 'flex';
            form_update.querySelector('[name="id"]').value = response.comment.id;
            form_update.querySelector('[name="author"]').value = response.comment.author;
            form_update.querySelector('[name="message"]').value = response.comment.message;
            /*
form_update.querySelector - через этот атрибут находим конкретные элементы в файле HTML. 
('[name="author"]') = input type="text" name="author" placeholder="Name" required 
value = response.comment.author - здесь мы возрашаем уже то, что нам нужно.
            */
        });
    };

    comment_block.append(new_comment);
}

/* Функция которая добовляет картинки к нашему фону*/
function addBackgroundImage (src) {
    const new_image = image_template.cloneNode();
    new_image.classList.remove('template');
    new_image.src = src;

    background_container.append(new_image);
}

let alt_is_down = false;
form.querySelector('textarea').onkeydown = function (event) {
    if (event.key === 'Alt') {
        alt_is_down = true;
    }
}
form.querySelector('textarea').onkeyup = function (event) {
    if (event.key === 'Alt') {
        alt_is_down = false;
    }
    else if (event.key === 'Enter') {
        if (alt_is_down === false) {
            submitForm(form);
        }
        else {
            this.value += '\n';
        }
    }
};

/* функция отвечающая за то, что нажимая на элемент popupб он пропадает */
popup.onclick = function(event) {
    if (event.target == this) {
        this.style.display = 'none';
    }
/* if (event.target == this)  
event.target - содержит элемент, на котором сработало событие (клик к примеру);
*/
};

/* Функция, чтобы картинки появлялись в веб-браузере на обоях

xhttp.get('api.php?object=image&action=getAll', function (response) {
    for (let image of response.images) {
        addBackgroundImage('endpoint.php?name=png&id=' + image.id);
'endpoint.php?name=png&id='- это путь к нашим файлам, если кто хочет обратиться только к ним, вписав в строку поиска имя файла на прямую.
К примеру, чтобы можно было бы скинуть ссылку на картинку.
    }
});
*/