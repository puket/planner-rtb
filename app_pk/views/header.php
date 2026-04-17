<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo isset($meta_description)?$meta_description:''?>">
    <meta name="author" content="Rotibit.Com">
    <link rel="icon" href="<?php echo base_url('assets/icon/favicon.png?tm=').time();?>">
    <title><?php echo isset($meta_title)?$meta_title.' - ':''?>Admin | Rotibit.Com</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets/bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/blog.css?tm=').time();?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/custom.css?tm=').time();?>" rel="stylesheet">

    <meta property="og:title" content="<?php echo isset($meta_title)?$meta_title.' | ':''?>Rotibit.Com"/>
    <meta property="og:description" content="<?php echo isset($meta_description)?$meta_description.' | ':''?>Rotibit.Com" />
    <meta property="og:url" content="<?php echo (isset($og_url)?$og_url:base_url('/'));?>"/> 
    <meta property="og:image" content="<?php echo (isset($og_image)?$og_image:base_url('assets/img/og_image.jpg'));?>" />
    <meta property="og:type" content="product"/> 
    <meta property="og:site_name" content="Rotibit.Com"/> 
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets/js/vendor/popper.min.js');?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/vendor/holder.min.js');?>"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>
        
  </head>
  <body>
  <?php include('inc_header.php');?>
