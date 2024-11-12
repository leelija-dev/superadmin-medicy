
      let searchReult = document.getElementById('searchReult');
      function imu(x){
         if (x.length ==0 ){
            searchReult.innerHTML = ''
         }
         else{
            var XML = new XMLHttpRequest();
            XML.onreadystatechange = function(){
               if(XML.readyState == 4 && XML.status == 200){
                  searchReult.innerHTML = XML.responseText;
               }
            };
            XML.open('GET', 'search.php?data='+x, true);
            XML.send();
         }
      } 