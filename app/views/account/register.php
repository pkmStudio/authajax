
   <div class="_container">
      <section class="section">
         <h1 class="section__title title">Регистрация</h1>

         <form class="section__form form" action="/register" method="POST">

            <label class="form__label" for="userName">Введите имя пользователя</label>
            <input class="form__input" id="userName" name="userName" type="text">

            <label class="form__label" for="birthDay">Введите дату рождения</label>
            <input class="form__input" id="birthDay" name="birthDay" type="date" required>

            <label class="form__label" for="image">Выберите изображение для аватарки</label>
            <input class="form__input" id="image" name="image" type="file" accept="image/*">

            <label class="form__label" for="login">Введите логин</label>
            <input class="form__input" id="login" name="login" type="text">

            <label class="form__label" for="password">Введите пароль</label>
            <input class="form__input" id="password" name="password" type="password">

            <label class="form__label" for="repeatPassword">Введите пароль</label>
            <input class="form__input" id="repeatPassword" name="repeatPassword" type="password">
         
            <div class="form__showpass _showpass">
               <label class="_showpass__label" for="showpass">Показать пароль</label>
               <input class="_showpass__checkbox" id="showpass" name="showpass" type="checkbox">
            </div>   

            <div class="form__input error-block _d-none"></div>
            
            <button class="form__button button" type="submit">Зарегистрироваться</button>
            <p class="form__text">
               У вас уже есть аккаунт? - <a href="/login">авторизуйтесь</a>!
            </p>
         </form>
      </section>
   </div>

