document.addEventListener("DOMContentLoaded", function () {
//=================
// Функция AJAX запроса
async function sendingAJAX(url, data='', method='POST', message='') {
   const response = await fetch(url, {
      method: method,
      body: data
   });

   if (response.ok) {
      if (message) {
         alert(`${message}: message`);
      }
      const result = await response.json();
      return result;
   } else {
      alert(`Ошибка: ${response.status}`);
      return response.status;
   }
}

//=================
// Функции отправки формы
function formSubmit() {
   const forms = document.querySelectorAll('form');
   if (!forms) {return 0;}

   forms.forEach(form => form.addEventListener('submit', formSend));
}

function formSend(e) {
   const form = e.target; //Сама форма
   const formAction = form.getAttribute('action') ? form.getAttribute('action').trim() : '#'; //Куда
   const formMethod = form.getAttribute('method') ? form.getAttribute('method').trim() : 'GET'; // Какой метод
   const formData = new FormData(form); // Что отправляем
   e.preventDefault(); // Отменяем перезагрузку
   form.classList.add('_sending'); // Навешиваем класс "ОТПРАВКА"

   sendingAJAX(formAction, formData, formMethod).then(data => processFormData(data, form));
}

//=================
// Функция Обработки полученных данных
function processFormData(data, form) {
   // form.reset();
   form.classList.remove('_sending');

   if (data.url) {
      window.location.href = data.url
   }

   if (data.status === "Error") {
      const errorBlock = form.querySelector('.error-block');
      errorBlock.classList.remove('_d-none');
      errorBlock.textContent = data.status + ": ";
      errorBlock.textContent += data.message;
   }

   if (data.status === "Done") {
      const section = document.querySelector('main');
      section.innerHTML = data.accountInfo;

      let div = document.createElement('div');
      div.className = "success-block";
      div.innerHTML = "Авторизация прошла успешно!";
      section.prepend(div);
      setTimeout(successLog, 10000);
   }
}

function successLog() {
   const successBlock = document.querySelector('.success-block');
   successBlock.classList.add('_d-none');
}
//=================
// Функция showPass
function showPass() {
   const showPassBox = document.querySelector('._showpass__checkbox');
   if (!showPassBox) {return 0;}

   showPassBox.addEventListener('click', () => {
      const passInput = document.querySelector('#password');
      if (passInput.type === 'password' && showPassBox.checked) {
         passInput.type = 'text';
      } else {
         passInput.type = 'password';
      }
   })
}

showPass();
formSubmit();
});