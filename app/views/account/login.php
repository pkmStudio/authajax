

   <div class="_container">
      <section class="section form-section">
         <h1 class="section__title title">Вход в личный кабинет</h1>
         <form class="section__form form" action="/login" method="POST">
            <label class="form__label" for="form">Введите логин</label>
            <input class="form__input" id="form" name="login" type="text">

            <label class="form__label" for="password">Введите пароль</label>
            <input class="form__input" id="password" name="password" type="password">
         
            <div class="form__showpass _showpass">
               <label class="_showpass__label" for="showpass">Показать пароль</label>
               <input class="_showpass__checkbox" id="showpass" name="showpass" type="checkbox">
            </div>   

            <div class="form__input error-block _d-none"></div>
            
            <button class="form__button button" type="submit">Войти асинхронно!</button>
            <p class="form__text">
               У вас еще нет аккаунта? - <a href="/register">зарегистрируйтесь</a>!
            </p>
         </form>
      </section>
   </div>
