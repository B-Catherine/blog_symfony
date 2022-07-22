   const isNightActivated  = localStorage.getItem('night-activate');
   if (isNightActivated){
      document.getElementById('body').classList.add('night_mode');
      night_btn.innerText = "Mode jour";
   }

const night_btn = document.getElementById('night_btn');

night_btn.addEventListener("click", function () {

   if (document.getElementById('body').classList.contains('night_mode')) {
      document.getElementById('body').classList.remove('night_mode')
      night_btn.innerText = "Mode nuit";
      localStorage.setItem('night-activated', 'false')

      document.getElementById('css').href = "assets/css/style.css";
   } else {
      console.log("test");
      console.log(document.getElementById('css'));
      document.getElementById('body').classList.add('night_mode');
      night_btn.innerText = "Mode jour";
      localStorage.setItem('night-activated', 'true');
   }
});
