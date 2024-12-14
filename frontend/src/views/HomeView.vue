<template>
   <div class="position-relative">
    <span class="dot">
      <div @click="$router.push('menu') " class="picture">
      <img class="img" src="@/assets/img/logoFinal.png">
    </div>
    </span>
    </div>
</template>


<style scoped>

body{
  width: 100%;
  height: 100%;
  
}
 .dot  {
  height: 500px;
  width: 500px;
  background-color: #1d5357; 
  shape-outside: circle();
  clip-path: circle();
  display: flex;
  opacity: 0.8;
  justify-content: center;
  
  } 

  .position-relative{
    padding-top: 75px;
    position:relative;
    height: 100%;
    width: 100%;
    position: fixed; /* or absolute */
     left: 35%;
     right: 35%;
    align-items: center;
  }


  .picture{
    transition-timing-function: ease;
    transition-duration: 2s;
  }
  .img{
    padding-top: 100px;
    width: 300px;
    height: 200px;
    
  }

  .picture:hover{
    transform:translateY(50px);
    cursor: pointer;
  }

  

  

</style>