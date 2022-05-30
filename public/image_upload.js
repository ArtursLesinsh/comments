const form = document.getElementById('comments_form');
const output = document.getElementById('output');

/* Функция при которой файл отправляется на сервер и файл прибовляется к обоям */
form.onsubmit = function(event) {
    event.preventDefault();
    xhttp.postForm(this, function (response) {
        displayImage(response.id);
    });
}

/* функция, которая делает запрос, откуда нам брать картинки, то есть в файл api.php, а он уже дальше*/
xhttp.get('api.php?object=image&action=getAll', function (response) {
    for (let image of response.images) {
        displayImage(image.id);
    }
});
/*Функция для вывода изображения на главный экран.
Так же здесь же создаём элемент "button" и придаём ему функцию "deleteImage", которую перехватываем при помощи "onclick"
Так же создаём элемент "img" для вывода картинок на главный экран.
*/
function displayImage(id) {
    const div = document.createElement('div');
    div.classList.add('image_wraper');

    const button = document.createElement('button');
    button.classList.add('delete');
    button.dataset.id = id;
    button.textContent = 'x';
    button.onclick = deleteImage;

    const img = document.createElement('img');
    const url = 'endpoint.php?name=png&id=' + id;
    img.src = url
    img.width = 200;


    div.append(img,button);
    output.append(div);
}
/*
const div = document.createElement('div');
div.classList.add('image_wraper'); -сделали константу "div", чтобы мы смогли бы придать ей стиль и стилизовать её.
<---->
const img = document.createElement('img'); - создали константу img
img.src = url - придали переменной значение url
<---->
Ко всему прочему, создали элемент "button":
    const button = document.createElement('button');
    button.classList.add('delete');
    button.dataset.id = id;
    button.textContent = 'x';
    button.onclick = deleteImage;
И эти 2 элемнта вывели при помощи:
div.append(img,button); 
На главную страницу, точнее мы сначала помести эти 2 элемнта в элемнт "div", тем самым придав им стили ('image_wraper').
<---->
output.append(div); - здесь мы выводим все свойства "div" элемент 
*/

/* Функция по удалению картинки */
function deleteImage (event) {
    const btn = this;
    const id = this.dataset.id;
    const data = new FormData();
    data.set('id', id);
    xhttp.post('api.php?object=image&action=delete', data, function (response) {
        btn.parentNode.remove();
    });
}
/*
function deleteImage (event) {
const btn = this; - делаем переменную btn, чтобы могли использовать её в btn.parentNode.remove();
const id = this.dataset.id; - показываем, что id = dataset.id чтобы он знал какой элемнт удалять.
const data = new FormData(); - образует новый обьект
data.set('id', id); - для FormData() подаём "id"
xhttp.post('api.php?object=image&action=delete', data, function (response) { - 
- запрос на сервер, чтобы запросить нужную информацию картинки для удаления
    btn.parentNode.remove(); - 
    parentNode - получаем при его помощи div элемент
    remove - уже удаляем его

    get - если хотим только что-то получить, но не менять, то используем это
    post - если хотим что-то поменять на стороне сервера, то используем это
 */