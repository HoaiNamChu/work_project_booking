
<style>
 @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Dancing+Script:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Roboto', sans-serif;

}

h1, h2 {
  font-family: 'Roboto', sans-serif;

  font-weight: 400;
}

a {
  text-decoration: none;
}

li {
  list-style: none;
  margin: 0;
  padding: auto;
}

.flex {
  display: flex;
}

.flex_space {
  display: flex;
  justify-content: space-between;
}

button {
  border: none;
  background: none;
  outline: none;
  transition: 0.5s;
  cursor: pointer;
}

.btn {
  padding: 10px 20px;  
  background: #B88A44;
  font-weight: bold;
  color: white;
}
.btn:hover{
  background-color: #965b02;
    color: #fff;
}

.secondary-btn {
  padding: 15px 40px;
  background: none;
  border: 2px solid white;
  font-weight: bold;
  color: white;
}
.secondary-btn:hover{
  background-color: #a09688;
    color: #fff;
}
.container {
  max-width: 85%;
  margin: auto;
}

/*--------header---------*/
header {
  height: 10vh;
  line-height: 10vh;
  padding: 0 20px;
  margin-bottom: 10px;
  box-shadow: 0px 4px 10px rgba(39, 38, 38, 0.04);
  position: fixed; /* Giữ header cố định khi cuộn trang */
  width: 100%; /* Đảm bảo header phủ toàn bộ chiều rộng */
  top: 0;
  z-index: 1000;
  background: white;
}

header img {
  margin: 20px 0;
}

header ul {
  display: inline-block;
}

header ul li {
  display: inline-block;
  text-transform: uppercase;
}

header ul li a {
  color: #000;
  margin: 0 10px;
  transition: 0.5s;
}

header ul li a:hover {
  color: #B88A44;
}

header i {
  margin: 0 20px;
}

header button {
  padding: 13px 40px;
  margin-bottom: 10px;
}

header .navlinks span {
  display: none;
}

@media only screen and (max-width:768px) {
  header ul {
    position: absolute;
    top: 100px;
    left: 0;
    width: 100%;
    height: 100vh;
    background: #B88A44;
    overflow: hidden;
    transition: max-height 0.5s;
    text-align: center;
    z-index: 9;
  }

  header ul li {
    display: block;
  }

  header ul li a {
    color: rgb(24, 22, 22);
  }

  header i {
    color: black;
  }

  header .navlinks span {
    color: bac;
    display: block;
    cursor: pointer;
    line-height: 10vh;
    font-size: 25px;
  }
}

/*--------header---------*/

</style>
<header><div class="content flex_space">
      <div class="logo">
        <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">
        <span><b>GOLDEN TREE APARTMENT</b></span>
        </a>
      </div>
      <div class="navlinks">
        <ul id="menulist">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#rooms">Rooms</a></li>
          <li>
          <div style="color:black; position:relative; margin-right:10px;">
    <a href="{{ route('cart') }}" title="Giỏ hàng" style="cursor:pointer; color:black; position:relative; display:inline-block;">
        <i class="fa-solid fa-cart-shopping fa-lg"></i>
        <div id="cart-number-product"
             style="width:14px; height:14px; background-color:#B88A44; font-size:9px;
                    border:none; border-radius:50%; position:absolute; top:10px; right:10px;
                    display:flex; align-items:center; justify-content:center; color:white;">
             {{ session('bookedRooms') ? count(session('bookedRooms')) : 0 }}
        </div>
    </a>
</div>

          </li>
          <li>
                     @if(session('user'))
                         <a href="#">
                             {{session('user')->ten}}
                         </a>
                         <a href="{{route('logout')}}">
                             <button class="btn">Logout</button>
                         </a>
                     @else
                         <a href="{{route('login')}}">
                             <button class="btn">Login</button>
                         </a>
                     @endif
                 </li>
        </ul>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @stack('scripts')
  <script>
    $(document).ready(function () {
        $.ajax({
            url: "{{ route('cart.count') }}",
            type: "GET",
            success: function (count) {
                $('#cart-number-product').text(count);
            }
        });
    });
  </script>
</header>


