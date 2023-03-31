function fromServer(data, url, load_page = false) {
    return new Promise((resolve, reject) => {
        let qery = new XMLHttpRequest();
        qery.onload = function () {
            let status = qery.status;
            let reqest = qery.responseText;
            if (load_page) {
                insertNewDiv(reqest);
            }
            if (status >= 200 && status < 300) {
                resolve(status);
            } else {
                reject(status);
            }
        };
        qery.onerror = function () {
            reject(Error("Network Error"));
        };
        qery.open("POST", url, true);
        qery.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        qery.send("user_url=" + data);
    });
}

function insertNewDiv(html) {
    var newDiv = document.createElement("main");
    newDiv.innerHTML = html;
    var oldDiv = document.querySelector('main');
    oldDiv.insertAdjacentElement("beforebegin", newDiv);
    oldDiv.parentNode.removeChild(oldDiv);
}

let isSubmitting = false;
document.body.addEventListener("submit", function (event) {
    event.preventDefault();
    let form = document.getElementById("form");
    if (!isSubmitting) {
        isSubmitting = true;
        fromServer(form[0].value, '/short', true).finally(() => {
            isSubmitting = false;
        });
    }
});

// Получаем ссылку на элемент и добавляем обработчик события при нажатии на кнопку
function copy() {
    // Получаем текст, который нужно скопировать
    var text = document.querySelector('#text').innerText;

    // Создаем временный элемент textarea для копирования текста в буфер обмена
    var tempElement = document.createElement('textarea');
    tempElement.value = text;
    document.body.appendChild(tempElement);

    // Выполняем команду копирования и удаляем временный элемент
    tempElement.select();
    document.execCommand('copy');
    document.body.removeChild(tempElement);
}