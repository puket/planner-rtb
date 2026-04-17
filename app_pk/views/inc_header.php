<div class="container">
  <header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <a class="text-muted" href="<?php echo base_url('dashb');?>">Dashboard</a>
      </div>
      <div class="col-4 text-center">
        <a class="blog-header-logo text-dark" href="<?php echo base_url('dashboard');?>" title="Shopping at Puket store">Admin</a>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <?php /* <a class="text-muted" href="#">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
        </a>*/ if($this->session->userdata('admlogged')){?>
          <a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url('logout');?>">Logout</a>
         <?php }?>
      </div>
    </div>
  </header>
  
  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between" style=" font-weight: bold; ">
      <a class="p-2 text-muted1" href="<?php echo base_url('shorturl/helper');?>">Helper</a>
      <a class="p-2 text-muted1" href="#">|</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('shorturl');?>">Short urls</a>
      <a class="p-2 text-muted1" href="#">|</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('admlazada/lazada_voucher');?>?slug=">LZ Campaign</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('prods/lz_prodbyhtml');?>">LZ Html</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('prods/lz_prodbyhand');?>">LZ Urls  </a>
      <?php /* <a class="p-2 text-muted1" href="<?php echo base_url('prods/lz_shopbyhtml');?>">Shop LZ </a> */?>
      <a class="p-2 text-muted1" href="#">|</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('admshopee/shopee_campaign');?>">SP Campaign</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('prods/sp_prodbyhtml');?>">SP Html</a>
      <a class="p-2 text-muted1" href="<?php echo base_url('prods/sp_prodbyhand');?>">SP Urls</a>
    </nav>
  </div>  
</div>