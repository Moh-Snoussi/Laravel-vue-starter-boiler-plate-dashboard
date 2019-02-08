<template>
  <mdb-container>
<mdb-navbar class="mt-2 navbar-2" dark color="primary">
        <mdb-navbar-brand class="text-white">
          Navbar
        </mdb-navbar-brand>
        <mdb-navbar-toggler>
          <mdb-navbar-nav right>
            <mdb-nav-item href="#" waves-fixed>REGISTRATION</mdb-nav-item>
            <mdb-nav-item href="#" waves-fixed>LOGIN</mdb-nav-item>
            <mdb-nav-item href="#" waves-fixed>HOME</mdb-nav-item>
          </mdb-navbar-nav>
        </mdb-navbar-toggler>
      </mdb-navbar>

       </mdb-container>
</template>

<script>
import { mdbNavbar, mdbNavItem, mdbNavbarNav, mdbNavbarToggler, mdbContainer, mdbRow, mdbInput, mdbNavbarBrand, mdbIcon } from 'mdbvue';

export default {
  name: 'HamburgerMenuPage',
  components: {
    mdbNavbar,
    mdbNavItem,
    mdbNavbarNav,
    mdbNavbarToggler,
    mdbContainer,
    mdbRow,
    mdbInput,
    mdbNavbarBrand,
    mdbIcon
  }
};
</script>

<style>
.dark-blue-text {
  color: #0A38F5;
}
.dark-pink-text {
  color: #AC003A;
}
.dark-amber-text {
  color: #ff6f00;
}
.dark-teal-text {
  color: #004d40;
}
.navbar.navbar-1 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger6.svg?color=000');
}
.navbar.navbar-2 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger2.svg?color=fff');
}
.navbar.navbar-3 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger1.svg?color=6a1b9a');
}
.navbar.navbar-4 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger7.svg?color=BFE100');
}
.navbar.navbar-5 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger5.svg?color=f3e5f5');
}
.navbar.navbar-6 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger8.svg?color=E3005C');
}
.navbar.navbar-7 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger9.svg?color=FF2C00');
}
.navbar.navbar-8 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger4.svg?color=1729B0');
}
.navbar.navbar-9 .navbar-toggler-icon {
  background-image: url('https://mdbootstrap.com/img/svg/hamburger3.svg?color=00FBD8');
}
</style>
