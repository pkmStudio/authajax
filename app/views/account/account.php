   <div class="_container">
      <div class="section">
         <div class="account">
            <div class="account__image">
               <img src="<?= $_SESSION['authorize']['accountInfo']->image;?>" alt="твоя аватарка">
            </div>
            <div class="account__info">
               <p class="account__name">Мое имя: <?= $_SESSION['authorize']['accountInfo']->name;?></p>
               <p class="account__login">Мой логин: <?= $_SESSION['authorize']['accountInfo']->login;?></p>
               <p class="account__birthday">Дата рождения: <?= $_SESSION['authorize']['accountInfo']->birthday;?></p>
               <a class="account__button button" href="/logout">Выйти</a>
            </div>
         </div>
      </div>
   </div>
