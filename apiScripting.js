const apiUrl = 'http://localhost/NavMap/api/get_buildingApi.php'

fetch(apiUrl) 
  .then(response => {
     if(!response.ok){
        throw new Error('Network response is not ok');
      }
   return response.json();
   })

    .then(data => {
       console.log(data)
})
.catch(error => {
  console.error("THere was a problem with the fetch data operation");
})


  