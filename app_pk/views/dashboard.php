<main role="main" class="container">
  <div class="row">
          <div class="col-md-12 blog-main">

            <h2>Dashboards</h2>
            <div class="col-md-12 row" style="margin-bottom: 15px;">
              <div class="table-responsive">
              	<table class="table table-striped table-sm">
              		<tr>
                    <th style="border: 1px solid #dee2e6;width:20%;">Title</th>
                    <th style="border: 1px solid #dee2e6;"></th>
              		</tr>
              		<tr>
                    <th style="border: 1px solid #dee2e6;">New today</th>
              			<td style="border: 1px solid #dee2e6;">
                      <a href="<?php echo base_url('prods/prods_today');?>" target='_blank'><?php echo number_format($stat['prod_today']);?> </a>
                    </td>
                  </tr>
              		<tr>
                    <th style="border: 1px solid #dee2e6;">New yesterday</th>
              			<td style="border: 1px solid #dee2e6;">
                      <a href="<?php echo base_url('prods/prods_yesterday');?>" target='_blank'><?php echo number_format($stat['prod_yesterday']);?></a> | 
                      <a href="<?php echo base_url('prods/prods_all');?>" target='_blank'>All </a>
                    </td>
                  </tr>
                  
                  <tr>
                    <th style="border: 1px solid #dee2e6;">Google Site</th>
                    <td style="border: 1px solid #dee2e6;">
                    <a href='https://www.google.com/search?q=site%3Apuketstores.com' target='_blank'>PKS</a>(<?php echo number_format($stat['prod_pks']);?>) |
                    <a href='https://www.google.com/search?q=site%3Awww.rotibit.com' target='_blank'>RTB</a>(<?php echo number_format($stat['prod_rtb']);?>) | 
                    <a href='https://www.google.com/search?q=site%3Aakinho.com' target='_blank'>AKH</a>(<?php echo number_format($stat['prod_akh']);?>) | 
                    <a href='https://www.google.com/search?q=site%3Awww.yupensuk.co.th' target='_blank'>YPS</a> |
                    <a href='https://www.google.com/search?q=site%3Abuysellthailand.com' target='_blank'>BST</a> | 
                    <a href='https://www.google.com/search?q=site%3Apuket24.com' target='_blank'>PK24</a> |
                    <?php /*
                    <a href='https://www.google.com/search?q=site%3Atimeandfeeling.com' target='_blank'>TMF</a>(<?php echo number_format($stat['prod_tmf']);?>) |
                    <a href='https://www.google.com/search?q=site%3Agoodhouseideas.com' target='_blank'>GDH</a>(<?php echo number_format($stat['prod_ghd']);?>) |
                    */ ?>
                  </td>
              		</tr>
              	</table>
              </div>              
            </div>
        </div>
  </div>
</main>