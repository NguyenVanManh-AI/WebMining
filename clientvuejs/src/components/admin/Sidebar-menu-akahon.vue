<template>
  <div class="sidebar" :class="isOpened ? 'open' : ''" :style="cssVars">
    <div class="logo-details" style="margin: 10px 14px 0 14px;">
      <img src="../../assets/logo.png" alt="menu-logo" class="menu-logo icon" style="" @click="compadmin">
      <!-- <i v-else class="bx icon" :class="menuIcon"/> -->
      <div class="logo_name" @click="compadmin"> Meta Shop </div>
      <i style="color: #0085FF;" class="bx" :class="isOpened ? 'bx-menu-alt-right' : 'bx-menu'" id="btn" @click="openN"/>
    </div>
    <div style="display: flex ; flex-direction:column; justify-content: space-between; flex-grow: 1; max-height: calc(100% - 60px); ">
      <div id="my-scroll" style="margin: 6px 14px 0 14px;">
        <ul class="nav-list" style="overflow: visible;">
          <!-- <li v-if="isSearch" @click="isOpened = true">
            <i class="bx bx-search" />
            <input type="text" :placeholder="searchPlaceholder" @input="$emit('search-input-emit', $event.target.value)">
            <span class="tooltip">{{ searchTooltip }}</span>
          </li> -->
          

          <div class="line"></div>
          <span>
            <li @click="profile">
              <a :class="{adefault:true,aclick:colors[11]}"><i class="fa-regular fa-address-card"></i><span class="links_name">Profile</span></a>
              <span class="tooltip">Profile</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="dashboard">
              <a :class="{adefault:true,aclick:colors[0]}"><i class="fa-solid fa-house"></i><span class="links_name">Dashboard</span></a>
              <span class="tooltip">Dashboard</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="product">
              <a :class="{adefault:true,aclick:colors[1]}"><i class='bx bxl-dropbox'></i><span class="links_name">Product</span></a>
              <span class="tooltip">Product</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="warehouse">
              <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" :class="{adefault:true,aclick:colors[2]}">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span class="links_name">Warehouse</span>
                <i class="fa-solid fa-chevron-right"></i>
              </a>
              <span class="tooltip">Warehouse </span>

              <div class="collapse" id="collapseExample" style="margin-left: 30px;">
                <div class="line"></div>
                <span>
                  <li @click="detailsWarehouse">
                    <a :class="{adefault:true,aclick:colors[3]}" ><i class="fa-solid fa-table-list"></i><span class="links_name">Details</span></a>
                    <span class="tooltip">Details</span>
                  </li>
                </span>

                <div class="line"></div>
                <span>
                  <li @click="importWarehouse">
                    <a :class="{adefault:true,aclick:colors[4]}" ><i class="fa-solid fa-cart-flatbed"></i><span class="links_name">Import</span></a>
                    <span class="tooltip">Import</span>
                  </li>
                </span>
              </div>

            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="category">
              <a :class="{adefault:true,aclick:colors[5]}"><i class="fa-solid fa-list"></i><span class="links_name">Category</span></a>
              <span class="tooltip">Category</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="provider">
              <a :class="{adefault:true,aclick:colors[6]}"><i class="fa-sharp fa-solid fa-people-carry-box"></i><span class="links_name">Provider</span></a>
              <span class="tooltip">Provider</span>
            </li>
          </span>


          <div class="line"></div>
          <span>
            <li @click="order">
              <a :class="{adefault:true,aclick:colors[7]}"><i class="fa-solid fa-cart-arrow-down"></i><span class="links_name">Order</span></a>
              <span class="tooltip">Order</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="statistical">
              <a :class="{adefault:true,aclick:colors[8]}"><i class="fa-solid fa-chart-column"></i><span class="links_name">Statistical</span></a>
              <span class="tooltip">Statistical</span>
            </li>
          </span>

          <div class="line"></div>
          <span>
            <li @click="customer">
              <a :class="{adefault:true,aclick:colors[9]}"><i class="fa-solid fa-users-gear"></i><span class="links_name">Customer</span></a>
              <span class="tooltip">Customer</span>
            </li>
          </span>

          <div class="line" v-if="this.admin.role=='super admin'"></div>
          <span v-if="this.admin.role=='super admin'">
            <li @click="administrator">
              <a :class="{adefault:true,aclick:colors[10]}"><i class="fa-solid fa-user-shield"></i><span class="links_name">Administrator</span></a>
              <span class="tooltip">Administrator</span>
            </li>
          </span>

        </ul>
      </div>
      
      <div v-if="isLoggedIn" class="profile" >
        <!-- Sau này có thời gian thì làm thêm cái ảnh nữa -->
        <div class="profile-details">
          <!-- <img v-if="profileImg" :src="profileImg" alt="profileImg"> -->
          <!-- <i v-else class="bx bxs-user-rectangle"/> -->
          <div class="name_job"> 
            <div class="name">{{ inf.fullname }}</div>
            <div class="job">{{ inf.role }}</div>
          </div>
        </div>
        <i v-if="isExitButton" class="bx bx-log-out" id="log_out" @click="logout"/>
      </div>
    </div>

  </div>
</template>

<script>

// import useEventBus from '../../composables/useEventBus'

  export default {
    name: 'SidebarMenuAkahon',
    props: {
      //! Menu settings
      isMenuOpen: {
        type: Boolean,
        default: true,
      },
      menuTitle: {
        type: String,
        default: 'Akahon',
      },
      menuLogo: {
        type: String,
        default: '',
      },
      menuIcon: {
        type: String,
        default: 'bxl-c-plus-plus',
      },
      isPaddingLeft: {
        type: Boolean,
        default: true,
      },
       menuOpenedPaddingLeftBody: {
        type: String,
        default: '250px'
      },
      menuClosedPaddingLeftBody: {
        type: String,
        default: '78px'
      },

      //! Menu items
      // menuItems: {
      //   type: Array,
      //   default: () => [
      //     {
      //       link: 'http://localhost:8084/admin/dashboard',
      //       name: 'Dashboard',
      //       tooltip: 'Dashboard',
      //       icon: 'bx-grid-alt',
      //     },
      //     {
      //       link: 'http://localhost:8084/admin/infor-admin',
      //       name: 'User',
      //       tooltip: 'User',
      //       icon: 'bx-user',
      //     },
      //     {
      //       link: '#',
      //       name: 'Messages',
      //       tooltip: 'Messages',
      //       icon: 'bx-chat',
      //     },
      //     {
      //       link: '#',
      //       name: 'Analytics',
      //       tooltip: 'Analytics',
      //       icon: 'bx-pie-chart-alt-2',
      //     },
      //     {
      //       link: '#',
      //       name: 'File Manager',
      //       tooltip: 'Files',
      //       icon: 'bx-folder',
      //     },
      //     {
      //       link: '#',
      //       name: 'Order',
      //       tooltip: 'Order',
      //       icon: 'bx-cart-alt',
      //     },
      //     {
      //       link: '#',
      //       name: 'Saved',
      //       tooltip: 'Saved',
      //       icon: 'bx-heart',
      //     },
      //     {
      //       link: '#',
      //       name: 'Setting',
      //       tooltip: 'Setting',
      //       icon: 'bx-cog',
      //     },
      //   ],
      // },

      //! Search
      isSearch: {
        type: Boolean,
        default: true,
      },
      searchPlaceholder: {
        type: String,
        default: 'Search...',
      },
      searchTooltip: {
        type: String,
        default: 'Search',
      },

      //! Profile detailes
      profileImg: {
        type: String,
        default: require('../../assets/img/photo.jpg'),
      },
      isExitButton: {
        type: Boolean,
        default: true,
      },
      isLoggedIn: {
        type: Boolean,
        default: true,
      },

      //! Styles
      bgColor: {
        type: String,
        default: '#11101d',
      },
      secondaryColor: {
        type: String,
        default: '#1d1b31',
      },
      homeSectionColor: {
        type: String,
        default: '#e4e9f7',
      },
      logoTitleColor: {
        type: String,
        default: '#fff',
      },
      iconsColor: {
        type: String,
        default: '#fff',
      },
      itemsTooltipColor: {
        type: String,
        default: '#e4e9f7',
      },
      searchInputTextColor: {
        type: String,
        default: '#fff',
      },
      menuItemsHoverColor: {
        type: String,
        default: '#fff',
      },
      menuItemsTextColor: {
        type: String,
        default: '#fff',
      },
      menuFooterTextColor: {
        type: String,
        default: '#fff',
      },
    },
    data() {
      return {
        isOpened: false,
        colors:[false,false,false,false,false,false,false,false,false,false,false],
        admin:{
          id:null,
          fullname:'',
          username:'',
          email: '',
          phone: '',
          date_of_birth:null,
          url_img:null,
          gender:null,
          address:'',
          role:'',
          access_token:'',
          refreshToken:'',
          created_at:null,
          updated_at:null,
          email_verified_at:null,
        },
        inf:{
            role:'',
            fullname:'',
        },
      }
    },
    methods:{

      openN:function(){
        this.isOpened = !this.isOpened
      },
      dashboard:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[0]=true;
        this.$router.push({name:'DashboardAdmin'});
      },
      product:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[1]=true;
        this.$router.push({name:'ProductAdmin'});
      },
      warehouse:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[2]=true;
      },
      detailsWarehouse:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[2]=true;
        this.colors[3]=true;
        this.$router.push({name:'WareHouse'});
      },
      importWarehouse:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[2]=true;
        this.colors[4]=true;
        this.$router.push({name:'WarehouseImport'});
      },
      category:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[5]=true;
        this.$router.push({name:'CategoryAdmin'});
      },
      provider:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[6]=true;
        this.$router.push({name:'ProviderAdmin'});
      },
      order:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[7]=true;
        this.$router.push({name:'AdminWaitForConfirmation'});
      },
      statistical:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[8]=true;
        this.$router.push({name:'StatisticalAdmin'});
      },
      customer:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[9]=true;
        this.$router.push({name:'CustomerAdmin'});
      },
      administrator:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[10]=true;
        this.$router.push({name:'ManagementAdmin'});
      },
      profile:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        this.colors[11]=true;

        this.$router.push({name:'ProfileAdmin'});
        
      },
      compadmin:function(){
        for(var i=0;i<this.colors.length;i++) this.colors[i] = false;
        window.location = window.location.origin + '/main/dashboard';
      },
      logout:function(){
        // const { emitEvent } = useEventBus()
        window.localStorage.removeItem('admin');
        this.$router.push({name:"LoginAdmin"});
        window.location = window.origin+'/admin/login';
        // emitEvent('eventLogout');
      }
    },
    mounted() {
      this.isOpened = this.isMenuOpen;
      this.admin = JSON.parse(window.localStorage.getItem('admin'));
      this.inf.fullname = this.admin.fullname;
      this.inf.role = this.admin.role;
    },
    computed: {
      cssVars() {
        return {
          // '--padding-left-body': this.isOpened ? this.menuOpenedPaddingLeftBody : this.menuClosedPaddingLeftBody,
          '--bg-color': this.bgColor,
          '--secondary-color': this.secondaryColor,
          '--home-section-color': this.homeSectionColor,
          '--logo-title-color': this.logoTitleColor,
          '--icons-color': this.iconsColor,
          '--items-tooltip-color': this.itemsTooltipColor,
          '--serach-input-text-color': this.searchInputTextColor,
          '--menu-items-hover-color': this.menuItemsHoverColor,
          '--menu-items-text-color': this.menuItemsTextColor,
          '--menu-footer-text-color': this.menuFooterTextColor,
        }
      },
    },
    watch: {
      isOpened() {
        window.document.body.style.paddingLeft = this.isOpened && this.isPaddingLeft ? this.menuOpenedPaddingLeftBody : this.menuClosedPaddingLeftBody
        var collapseExample = window.document.getElementById('collapseExample');
        if(this.isOpened == false){
          collapseExample.style='margin-left: 0px;';
        }
        if(this.isOpened == true){
          collapseExample.style='margin-left: 30px;';
        }
      }
    }
  }
</script>

<style scoped>
  /* Google Font Link */
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
  @import url('https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css');
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }
  body {
    transition: all 0.5s ease;
  }
  .adefault{
    background: #0085FF;
  }
  .aclick{
    background: #07131d;
  }
  .line {
    width: 100%;
    height: 2px;
    background-color: white;
    margin: 6px 0px;
    border: 1px solid white;

  }
  .menu-logo {
    width: 30px;
    margin: 0 10px 0 10px;
    cursor: pointer;
  }
  .sidebar {
    position: relative;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    min-height: min-content;
    /* overflow-y: auto; */
    width: 78px;
    background: #0085FF;
    /* padding: 6px 14px 0 14px; */
    z-index: 99;
    transition: all 0.5s ease;
  }
  .sidebar.open {
    width: 250px;
  }
  .sidebar .logo-details {
    height: 60px;
    display: flex;
    align-items: center;
    position: relative;
    background-color: white;
    color: #0085FF;
    border-radius: 12px ;
  }
  .sidebar .logo-details .icon {
    opacity: 0;
    transition: all 0.5s ease;
  }
  .sidebar .logo-details .logo_name {
    cursor: pointer;
    color: #0085FF;
    font-size: 20px;
    font-weight: 600;
    opacity: 0;
    transition: all 0.5s ease;
  }
  .sidebar.open .logo-details .icon,
  .sidebar.open .logo-details .logo_name {
    opacity: 1;
  }
  .sidebar .logo-details #btn {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    font-size: 22px;
    transition: all 0.4s ease;
    font-size: 23px;
    text-align: center;
    cursor: pointer;
    transition: all 0.5s ease;
  }
  .sidebar.open .logo-details #btn {
    text-align: right;
  }
  .bx-menu-alt-right::before{
    margin-right: 10px;
  }
  .sidebar i {
    color: var(--icons-color);
    height: 60px;
    min-width: 50px;
    font-size: 28px;
    text-align: center;
    line-height: 60px;
  }
  .sidebar .nav-list {
    margin-top: 20px;
    /* margin-bottom: 60px; */
    /* height: 100%; */
    /* min-height: min-content; */
  }
  .sidebar li {
    position: relative;
    margin: 8px 0;
    list-style: none;
    cursor: pointer;
  }
  .sidebar li .tooltip {
    position: absolute;
    top: -20px;
    left: calc(100% + 15px);
    z-index: 3;
    background: var(--items-tooltip-color);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 400;
    opacity: 0;
    white-space: nowrap;
    pointer-events: none;
    transition: 0s;
  }
  .sidebar li:hover .tooltip {
    opacity: 1;
    pointer-events: auto;
    transition: all 0.4s ease;
    top: 50%;
    transform: translateY(-50%);
  }
  .sidebar.open li .tooltip {
    display: none;
  }
  .sidebar input {
    font-size: 15px;
    color: var(--serach-input-text-color);
    font-weight: 400;
    outline: none;
    height: 50px;
    width: 100%;
    width: 50px;
    border: none;
    border-radius: 12px;
    transition: all 0.5s ease;
    background: var(--secondary-color);
  }
  .sidebar.open input {
    padding: 0 20px 0 50px;
    width: 100%;
  }
  .sidebar .bx-search {
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    font-size: 22px;
    background: var(--secondary-color);
    color: var(--icons-color);
  }
  .sidebar.open .bx-search:hover {
    background: var(--secondary-color);
    color: var(--icons-color);
  }
  .sidebar .bx-search:hover {
    background: var(--menu-items-hover-color);
    color: var(--bg-color);
  }
  .sidebar li a {
    display: flex;
    height: 100%;
    width: 100%;
    border-radius: 12px;
    align-items: center;
    text-decoration: none;
    transition: all 0.4s ease;
    /* background: #0085FF; */
  }
  .sidebar li a:hover {
    background-color: white;
  }
  .sidebar li a .links_name {
    color: var(--menu-items-text-color);
    font-size: 15px;
    font-weight: 400;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: 0.4s;
  }
  .sidebar.open li a .links_name {
    opacity: 1;
    pointer-events: auto;
  }
  .sidebar li a:hover .links_name,
  .sidebar li a:hover i {
    transition: all 0.5s ease;
    color: #0085FF;
  }
  .sidebar li i {
    height: 50px;
    line-height: 50px;
    font-size: 18px;
    border-radius: 12px;
  }
  .sidebar div.profile {
    position: relative;
    height: 60px;
    width: 78px;
    /* left: 0;
    bottom: 0; */
    padding: 10px 14px;
    background: var(--secondary-color);
    transition: all 0.5s ease;
    overflow: hidden;
  }
  .sidebar.open div.profile {
    width: 250px;
  }
  .sidebar div .profile-details {
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
  }
  .sidebar div img {
    height: 45px;
    width: 45px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 10px;
  }
  .sidebar div.profile .name,
  .sidebar div.profile .job {
    font-size: 15px;
    font-weight: 400;
    color: var(--menu-footer-text-color);
    white-space: nowrap;
  }
  .sidebar div.profile .job {
    font-size: 12px;
  }
  .sidebar .profile #log_out {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    background: var(--secondary-color);
    width: 100%;
    height: 60px;
    line-height: 60px;
    border-radius: 0px;
    transition: all 0.5s ease;
  }
  .sidebar.open .profile #log_out {
    width: 50px;
    background: var(--secondary-color);
    opacity: 0;
  }
  .sidebar.open .profile:hover #log_out {
    opacity: 1;
  }
  .sidebar.open .profile #log_out:hover {
    opacity: 1;
    color: red;
  }
  .sidebar .profile #log_out:hover {
    color: red;
  }
  .home-section {
    position: relative;
    background: var(--home-section-color);
    min-height: 100vh;
    top: 0;
    left: 78px;
    width: calc(100% - 78px);
    transition: all 0.5s ease;
    z-index: 2;
  }
  .sidebar.open ~ .home-section {
    left: 250px;
    width: calc(100% - 250px);
  }
  .home-section .text {
    display: inline-block;
    color: var(--bg-color);
    font-size: 25px;
    font-weight: 500;
    margin: 18px;
  }
  .my-scroll-active {
    overflow-y: auto;
  }
  #my-scroll {
    overflow-y: auto;
    height: calc(100% - 60px);
  }
  #my-scroll::-webkit-scrollbar{
    display:none;
    /* background-color: rgba(255, 255, 255, 0.2); 
    width: 10px;
    border-radius:5px  */
  }
  /* #my-scroll::-webkit-scrollbar-thumb{
    background-color: red;
    border-radius:5px 
  }
  #my-scroll::-webkit-scrollbar-button:vertical:start:decrement{
    display:none;
  }
  #my-scroll::-webkit-scrollbar-button:vertical:end:increment{
    display:none;
  } */
  @media (max-width: 420px) {
    .sidebar li .tooltip {
      display: none;
    }
}
/* @media print{
  .sidebar { 
    display: none;
    padding: 0px;
    margin: 0px;
  }
  body {
    padding:0px;
  }
} */
</style>
